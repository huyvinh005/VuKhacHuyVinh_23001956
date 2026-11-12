"""Kiểm tra API thật; chỉ tạo/xóa dữ liệu thử có tên ngẫu nhiên."""
import argparse
import json
import unittest
from urllib.error import HTTPError
from urllib.parse import urljoin
from urllib.request import Request, urlopen
from uuid import uuid4


BASE_URL = 'http://127.0.0.1:18768/'


class ProductsApiTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.created_ids = set()
        cls.prefix = 'Test ' + uuid4().hex[:12]

    def request(self, method='GET', body=None, product_id=None, raw=None):
        url = urljoin(BASE_URL, 'api/products.php')
        if product_id is not None:
            url += '?id=' + str(product_id)
        data = raw if raw is not None else (
            json.dumps(body, ensure_ascii=False).encode('utf-8') if body is not None else None
        )
        request = Request(url, data=data, method=method)
        if data is not None:
            request.add_header('Content-Type', 'application/json')
        try:
            response = urlopen(request, timeout=20)
        except HTTPError as error:
            response = error
        with response:
            self.assertEqual(response.headers.get_content_type(), 'application/json')
            result = json.loads(response.read().decode('utf-8'))
            self.assertIsInstance(result.get('success'), bool)
            if not result['success']:
                self.assertIsInstance(result.get('message'), str)
                self.assertTrue(result['message'])
            return response.status, result

    def products(self):
        status, result = self.request()
        self.assertEqual(status, 200, result)
        self.assertTrue(result['success'])
        self.assertIsInstance(result['data'], list)
        return result['data']

    def create_product(self, **overrides):
        product = {'name': self.prefix + ' Bút chì', 'price': 7000, 'stock': 10}
        product.update(overrides)
        status, result = self.request('POST', product)
        if result.get('success') and isinstance(result.get('data'), dict) and 'id' in result['data']:
            self.created_ids.add(result['data']['id'])
        self.assertEqual(status, 201, result)
        self.assertTrue(result['success'])
        return result['data']

    def test_01_list_and_integer_fields(self):
        for product in self.products():
            for field in ['id', 'price', 'stock']:
                self.assertIs(type(product[field]), int)
            self.assertIsInstance(product['name'], str)

    def test_02_persist_create_update_noop_and_delete(self):
        product = self.create_product()
        product_id = product['id']
        self.assertIn(product, self.products(), 'Sản phẩm thêm phải còn ở GET mới')
        payload = {'name': product['name'], 'price': 9000, 'stock': 0}
        for _ in range(2):
            status, result = self.request('PUT', payload, product_id)
            self.assertEqual(status, 200, result)
            self.assertTrue(result['success'])
            saved = next(row for row in self.products() if row['id'] == product_id)
            self.assertEqual(saved, {'id': product_id, **payload})
        status, result = self.request('DELETE', product_id=product_id)
        self.assertEqual(status, 200, result)
        self.assertTrue(result['success'])
        self.created_ids.discard(product_id)
        self.assertNotIn(product_id, [row['id'] for row in self.products()])

    def test_03_negative_stock_is_rejected_without_insert(self):
        before = self.products()
        status, result = self.request('POST', {'name': self.prefix, 'price': 5000, 'stock': -1})
        self.assertEqual(status, 422, result)
        self.assertFalse(result['success'])
        self.assertEqual(self.products(), before)

    def test_04_invalid_fields_never_insert(self):
        valid = {'name': self.prefix, 'price': 5000, 'stock': 0}
        cases = [
            {'name': ''}, {'name': '   '}, {'name': 'á' * 101}, {'name': 123},
            {'price': 0}, {'price': -1}, {'price': 1.5}, {'price': '5000'},
            {'price': True}, {'price': None}, {'price': 2147483648},
            {'stock': -1}, {'stock': 0.5}, {'stock': '1'}, {'stock': False},
            {'stock': None}, {'stock': 2147483648},
        ]
        before = self.products()
        for overrides in cases:
            with self.subTest(overrides=overrides):
                status, result = self.request('POST', {**valid, **overrides})
                self.assertEqual(status, 422, result)
                self.assertFalse(result['success'])
                self.assertEqual(self.products(), before)
        for field in valid:
            status, result = self.request('POST', {key: value for key, value in valid.items() if key != field})
            self.assertEqual(status, 422, result)
            self.assertEqual(self.products(), before)

    def test_05_invalid_update_leaves_data_unchanged(self):
        product = self.create_product()
        status, result = self.request('PUT', {'name': self.prefix, 'price': 1000, 'stock': -1}, product['id'])
        self.assertEqual(status, 422, result)
        saved = next(row for row in self.products() if row['id'] == product['id'])
        self.assertEqual(saved, product)

    def test_06_unicode_trim_and_name_boundaries(self):
        for name in ['á', 'á' * 100, "Bút ' OR 1=1; --", '<script>alert(1)</script>']:
            with self.subTest(name=name):
                product = self.create_product(name='  ' + name + '  ', stock=0, price=1)
                self.assertEqual(product['name'], name)
                self.assertIn(product, self.products())

    def test_07_malformed_json_and_nonobject(self):
        for raw in [b'{', b'', b'null', b'[]', b'42', b'"test"']:
            with self.subTest(raw=raw):
                status, result = self.request('POST', raw=raw)
                self.assertEqual(status, 400, result)
                self.assertFalse(result['success'])

    def test_08_missing_or_invalid_id(self):
        payload = {'name': self.prefix, 'price': 1, 'stock': 0}
        for product_id in [None, '', 'abc', 0, -1, '1.5', '1%20OR%201=1', 2147483648]:
            for method in ['PUT', 'DELETE']:
                with self.subTest(method=method, product_id=product_id):
                    status, result = self.request(method, payload if method == 'PUT' else None, product_id)
                    self.assertEqual(status, 400, result)
                    self.assertFalse(result['success'])

    def test_09_nonexistent_id(self):
        used_ids = {row['id'] for row in self.products()}
        missing_id = 2147483647
        while missing_id in used_ids:
            missing_id -= 1
        for method in ['PUT', 'DELETE']:
            status, result = self.request(method, {'name': self.prefix, 'price': 1, 'stock': 0} if method == 'PUT' else None, missing_id)
            self.assertEqual(status, 404, result)
            self.assertFalse(result['success'])

    def test_10_unsupported_method(self):
        status, result = self.request('PATCH', {'name': self.prefix})
        self.assertEqual(status, 405, result)
        self.assertFalse(result['success'])

    @classmethod
    def tearDownClass(cls):
        failures = []
        for product_id in cls.created_ids:
            try:
                url = urljoin(BASE_URL, 'api/products.php') + '?id=' + str(product_id)
                with urlopen(Request(url, method='DELETE'), timeout=20) as response:
                    if not json.load(response)['success']:
                        failures.append(product_id)
            except Exception:
                failures.append(product_id)
        if failures:
            raise RuntimeError('Chưa dọn được các bản ghi kiểm tra có id: ' + str(failures))


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--base-url', default=BASE_URL)
    arguments, remaining = parser.parse_known_args()
    BASE_URL = arguments.base_url.rstrip('/') + '/'
    unittest.main(argv=['check_api.py', *remaining], verbosity=2)
