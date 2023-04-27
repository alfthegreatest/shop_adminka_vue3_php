<?php

// Роутинг, основная функция
function route($data) {
    // GET /brands
    if ($data['method'] === 'GET' && $data['urlData']) {
        echo json_encode(getBrands());
        exit;
    }

    // POST /brands
    if ($data['method'] === 'POST' && $data['urlData'] && isset($data['formData']['title'])) {
        $title = $data['formData']['title'];

        echo json_encode(addBrand($title));
        exit;
    }

    // PUT /brands/5
    if ($data['method'] === 'PUT' && $data['urlData'] && isset($data['formData']['title'])) {
        $id = (int)explode('/', $data['urlData'])[1];
        $title = $data['formData']['title'];

        echo json_encode(updateBrand($id, $title));
        exit;
    }

    // DELETE /brands/5
    if ($data['method'] === 'DELETE' && $data['urlData']) {
        $id = (int)explode('/', $data['urlData'])[1];
        echo json_encode(deleteBrand($id));
        exit;
    }


    // Если ни один роутер не отработал
    \Helpers\throwHttpError('invalid_parameters', 'invalid parameters');

}


// Возвращаем все бренды
function getBrands() {
    $pdo = \Helpers\connectDB();
    $query = 'select id, brand from brands';
    $data = $pdo->prepare($query);
    $data->execute();

    return array(
        'meta' => array(),
        'records' => __($data->fetchAll())->map(function($item) {
            return array(
                'id' => (int)$item['id'],
                'title' => $item['brand']
            );
        })
    );
}


// Добавление бренда
function addBrand($title) {
    $pdo = \Helpers\connectDB();

    // Если бренд существует, то выбрасываем ошибку
    if (\Helpers\isExistsBrandByTitle($pdo, $title)) {
        \Helpers\throwHttpError('brand_exists', 'brand already exists');
        exit;
    }

    // Добавляем бренд в базу
    $query = 'insert into brands (brand) values (:title)';
    $data = $pdo->prepare($query);
    $data->bindParam(':title', $title);
    $data->execute();

    // Новый айдишник для добавленного бренда
    $newId = (int)$pdo->lastInsertId();

    return array(
        'id' => $newId,
        'title' => $title
    );
}


// Обновление бренда
function updateBrand($id, $title) {
    $pdo = \Helpers\connectDB();

    // Если бренд не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsBrandById($pdo, $id)) {
        \Helpers\throwHttpError('brand_not_exists', 'brand not exists');
        exit;
    }

    // Обновляем бренд в базе
    $query = 'update brands set brand=:title where id=:id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->bindParam(':title', $title);
    $data->execute();

    return array(
        'id' => $id,
        'title' => $title
    );
}


// Удаление бренда
function deleteBrand($id) {
    $pdo = \Helpers\connectDB();

    // Если бренд не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsBrandById($pdo, $id)) {
        \Helpers\throwHttpError('brand_not_exists', 'brand not exists');
        exit;
    }


    // Удаляем бренд из базы
    $query = 'delete from brands where id=:id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->execute();

    return array(
        'id' => $id
    );
}
