<?php

// Роутинг, основная функция
function route($data) {

    // GET /categories
    if ($data['method'] === 'GET' && $data['urlData']) {
        echo json_encode(getCategories());
        exit;
    }

    // POST /categories
    if ($data['method'] === 'POST' && count($data['urlData']) === 1 && isset($data['formData']['title'])) {
        $title = $data['formData']['title'];

        echo json_encode(addCategory($title));
        exit;
    }

    // PUT /categories/5
    if ($data['method'] === 'PUT' && count($data['urlData']) === 2 && isset($data['formData']['title'])) {
        $id = (int)$data['urlData'][1];
        $title = $data['formData']['title'];

        echo json_encode(updateCategory($id, $title));
        exit;
    }

    // DELETE /categories/5
    if ($data['method'] === 'DELETE' && count($data['urlData']) === 2) {
        $id = (int)$data['urlData'][1];

        echo json_encode(deleteCategory($id));
        exit;
    }


    // Если ни один роутер не отработал
    \Helpers\throwHttpError('invalid_parameters', 'invalid parameters');

}


// Возвращаем все категории
function getCategories() {
    $pdo = \Helpers\connectDB();
    $query = 'select id, category from categories';
    $data = $pdo->prepare($query);
    $data->execute();

    return array(
        'meta' => array(),
        'records' => __($data->fetchAll())->map(function($item) {
            return array(
                'id' => (int)$item['id'],
                'title' => $item['category']
            );
        })
    );
}


// Добавление категории
function addCategory($title) {
    $pdo = \Helpers\connectDB();

    // Если категория существует, то выбрасываем ошибку
    if (\Helpers\isExistsCategoryByTitle($pdo, $title)) {
        \Helpers\throwHttpError('category_exists', 'category already exists');
        exit;
    }

    // Добавляем категорию в базу
    $query = 'insert into categories (category) values (:title)';
    $data = $pdo->prepare($query);
    $data->bindParam(':title', $title);
    $data->execute();

    // Новый айдишник для добавленной категории
    $newId = (int)$pdo->lastInsertId();

    return array(
        'id' => $newId,
        'title' => $title
    );
}


// Обновление категории
function updateCategory($id, $title) {
    $pdo = \Helpers\connectDB();

    // Если категория не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsCategoryById($pdo, $id)) {
        \Helpers\throwHttpError('category_not_exists', 'category not exists');
        exit;
    }

    // Обновляем категорию в базе
    $query = 'update categories set category=:title where id=:id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->bindParam(':title', $title);
    $data->execute();

    return array(
        'id' => $id,
        'title' => $title
    );
}


// Удаление категории
function deleteCategory($id) {
    $pdo = \Helpers\connectDB();

    // Если категория не существует, то выбрасываем ошибку
    if (!\Helpers\isExistsCategoryById($pdo, $id)) {
        \Helpers\throwHttpError('category_not_exists', 'category not exists');
        exit;
    }

    // Удаляем категорию из базы
    $query = 'delete from categories where id=:id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->execute();

    return array(
        'id' => $id
    );
}
