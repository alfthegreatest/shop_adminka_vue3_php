<?php

// Роутинг, основная функция
function route($data) {

    // GET /products
    if ($data['method'] === 'GET' && $data['urlData']) {
        $options = $data['formData'];

        echo json_encode(getProducts($options));
        exit;
    }

    // GET /products/5
    if ($data['method'] === 'GET' && count($data['urlData']) === 2) {
        $id = (int)$data['urlData'][1];

        echo json_encode(getProduct($id));
        exit;
    }

    // POST /products
    if (
        $data['method'] === 'POST' &&
        count($data['urlData']) === 1 &&
        isset($data['formData']['title']) &&
        isset($data['formData']['categoryId']) &&
        isset($data['formData']['brandId']) &&
        isset($data['formData']['price']) &&
        isset($data['formData']['rating'])
    ) {
        $title = $data['formData']['title'];
        $categoryId = (int)$data['formData']['categoryId'];
        $brandId = (int)$data['formData']['brandId'];
        $price = (int)$data['formData']['price'];
        $rating = (int)$data['formData']['rating'];

        echo json_encode(addProduct($title, $categoryId, $brandId, $price, $rating));
        exit;
    }

    // PUT /products/5
    if (
        $data['method'] === 'PUT' &&
        count($data['urlData']) === 2 &&
        isset($data['formData']['title']) &&
        isset($data['formData']['categoryId']) &&
        isset($data['formData']['brandId']) &&
        isset($data['formData']['price']) &&
        isset($data['formData']['rating'])
    ) {
        $id = (int)$data['urlData'][1];
        $title = $data['formData']['title'];
        $categoryId = (int)$data['formData']['categoryId'];
        $brandId = (int)$data['formData']['brandId'];
        $price = (int)$data['formData']['price'];
        $rating = (int)$data['formData']['rating'];

        echo json_encode(updateProduct($id, $title, $categoryId, $brandId, $price, $rating));
        exit;
    }

    // DELETE /products/5
    if ($data['method'] === 'DELETE' && count($data['urlData']) === 2) {
        $id = (int)$data['urlData'][1];

        echo json_encode(deleteProduct($id));
        exit;
    }


    // Если ни один роутер не отработал
    \Helpers\throwHttpError('invalid_parameters', 'invalid parameters');

}


// Возвращаем все товары
function getProducts($options) {
    $pdo = \Helpers\connectDB();
    $meta = array();
    $query = 'select g.id, g.good, g.category_id, g.brand_id, b.brand, g.price, g.rating from goods g, brands b where g.brand_id = b.id';

    // Фильтруем по категории
    if (
        isset($options['categoryId']) &&
        is_numeric($options['categoryId'])
    ) {
        $query .= ' and g.category_id = :categoryId';
        $meta['categoryId'] = (int)$options['categoryId'];
    }

    // Пагинация
    if (
        isset($options['offset']) &&
        is_numeric($options['offset']) &&
        isset($options['limit']) &&
        is_numeric($options['limit'])
    ) {
        $query .= ' limit :offset, :limit';
        $meta['offset'] = (int)$options['offset'];
        $meta['limit'] = (int)$options['limit'];
    }

    $data = $pdo->prepare($query);
    foreach ($meta as $key => $value) {
        $data->bindValue(':' . $key, $value, PDO::PARAM_INT);
    }
    $data->execute();

    return array(
        'meta' => $meta,
        'records' => __($data->fetchAll())->map(function($item) {
            return array(
                'id' => (int)$item['id'],
                'title' => $item['good'],
                'categoryId' => (int)$item['category_id'],
                'brandId' => (int)$item['brand_id'],
                'brand' => $item['brand'],
                'price' => (int)$item['price'],
                'rating' => (int)$item['rating']
            );
        })
    );
}


// Возвращаем информацию по одному товару
function getProduct($id) {
    $pdo = \Helpers\connectDB();

    // Если товар не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsProductById($pdo, $id)) {
        \Helpers\throwHttpError('product_not_exists', 'product not exists');
        exit;
    }

    $query = 'select g.id, g.good, g.category_id, g.brand_id, b.brand, g.price, g.rating from goods g, brands b where g.id=:id and g.brand_id = b.id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->execute();

    $item = $data->fetch();
    return array(
        'id' => $id,
        'title' => $item['good'],
        'categoryId' => (int)$item['category_id'],
        'brandId' => (int)$item['brand_id'],
        'brand' => $item['brand'],
        'price' => (int)$item['price'],
        'rating' => (int)$item['rating']
    );
}


// Добавление товара
function addProduct($title, $categoryId, $brandId, $price, $rating) {
    $pdo = \Helpers\connectDB();

    // Если товар существует, то выбрасываем ошибку
    if (\Helpers\isExistsProductByTitle($pdo, $title)) {
        \Helpers\throwHttpError('product_exists', 'product already exists');
        exit;
    }

    // Если категория не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsCategoryById($pdo, $categoryId)) {
        \Helpers\throwHttpError('category_not_exists', 'category not exists');
        exit;
    }

    // Если бренда не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsBrandById($pdo, $brandId)) {
        \Helpers\throwHttpError('brand_not_exists', 'brand not exists');
        exit;
    }

    // Добавляем товар в базу
    $query = 'insert into goods (good, category_id, brand_id, price, rating, photo) values (:title, :categoryId, :brandId, :price, :rating, :photo)';
    $data = $pdo->prepare($query);
    $data->bindParam(':title', $title);
    $data->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
    $data->bindParam(':brandId', $brandId, PDO::PARAM_INT);
    $data->bindParam(':price', $price, PDO::PARAM_INT);
    $data->bindParam(':rating', $rating, PDO::PARAM_INT);
    $data->bindValue(':photo', ''); // bindParam работает только с переменными
    $data->execute();

    // Новый айдишник для добавленного товара
    $newId = (int)$pdo->lastInsertId();
    return getProduct($newId);
}


// Обновление товара
function updateProduct($id, $title, $categoryId, $brandId, $price, $rating) {
    $pdo = \Helpers\connectDB();

    // Если товар не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsProductById($pdo, $id)) {
        \Helpers\throwHttpError('product_not_exists', 'product not exists');
        exit;
    }

    // Если категория не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsCategoryById($pdo, $categoryId)) {
        \Helpers\throwHttpError('category_not_exists', 'category not exists');
        exit;
    }

    // Если бренда не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsBrandById($pdo, $brandId)) {
        \Helpers\throwHttpError('brand_not_exists', 'brand not exists');
        exit;
    }

    // Обновляем товар в базе
    $query = 'update goods set good=:title, category_id=:categoryId, brand_id=:brandId, price=:price, rating=:rating where id=:id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->bindParam(':title', $title);
    $data->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
    $data->bindParam(':brandId', $brandId, PDO::PARAM_INT);
    $data->bindParam(':price', $price, PDO::PARAM_INT);
    $data->bindParam(':rating', $rating, PDO::PARAM_INT);
    $data->execute();

    return getProduct($id);
}


// Удаление товара
function deleteProduct($id) {
    $pdo = \Helpers\connectDB();

    // Если товар не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsProductById($pdo, $id)) {
        \Helpers\throwHttpError('product_not_exists', 'product not exists');
        exit;
    }

    // Удаляем товар из базы
    $query = 'delete from goods where id=:id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->execute();

    return array(
        'id' => $id
    );
}
