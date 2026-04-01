<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Quote.php';
require_once __DIR__ . '/models/Author.php';
require_once __DIR__ . '/models/Category.php';

$db = (new Database())->getConnection();

$path     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = array_values(array_filter(explode('/', $path)));
$api_idx  = array_search('api', $segments);
$resource = ($api_idx !== false && isset($segments[$api_idx + 1])) ? strtolower($segments[$api_idx + 1]) : '';
$method   = $_SERVER['REQUEST_METHOD'];
$body     = json_decode(file_get_contents('php://input'), true) ?? [];

function respond($data) { echo json_encode($data); exit; }

switch ($resource) {
    case 'quotes':     handleQuotes($db, $method, $_GET, $body);     break;
    case 'authors':    handleAuthors($db, $method, $_GET, $body);    break;
    case 'categories': handleCategories($db, $method, $_GET, $body); break;
    default: http_response_code(404); respond(['message' => 'Route Not Found']);
}

function handleQuotes($db, $method, $params, $body) {
    $model = new Quote($db);

    if ($method === 'GET') {
        $id          = isset($params['id'])          ? (int)$params['id']          : null;
        $author_id   = isset($params['author_id'])   ? (int)$params['author_id']   : null;
        $category_id = isset($params['category_id']) ? (int)$params['category_id'] : null;

        $rows = $model->get($id, $author_id, $category_id);
        if (empty($rows)) respond(['message' => 'No Quotes Found']);
        respond(count($rows) === 1 ? $rows[0] : $rows);
    }

    if ($method === 'POST') {
        $quote       = isset($body['quote'])       ? trim($body['quote'])       : null;
        $author_id   = isset($body['author_id'])   ? (int)$body['author_id']   : null;
        $category_id = isset($body['category_id']) ? (int)$body['category_id'] : null;

        if (empty($quote) || $author_id === null || $category_id === null) respond(['message' => 'Missing Required Parameters']);
        if (!(new Author($db))->exists($author_id))   respond(['message' => 'author_id Not Found']);
        if (!(new Category($db))->exists($category_id)) respond(['message' => 'category_id Not Found']);

        respond($model->create($quote, $author_id, $category_id));
    }

    if ($method === 'PUT') {
        $id          = isset($body['id'])          ? (int)$body['id']          : null;
        $quote       = isset($body['quote'])       ? trim($body['quote'])       : null;
        $author_id   = isset($body['author_id'])   ? (int)$body['author_id']   : null;
        $category_id = isset($body['category_id']) ? (int)$body['category_id'] : null;

        if (empty($quote) || $author_id === null || $category_id === null) respond(['message' => 'Missing Required Parameters']);
        if (!(new Author($db))->exists($author_id))   respond(['message' => 'author_id Not Found']);
        if (!(new Category($db))->exists($category_id)) respond(['message' => 'category_id Not Found']);

        if ($model->update($id, $quote, $author_id, $category_id) === 0) respond(['message' => 'No Quotes Found']);
        respond(['id' => $id, 'quote' => $quote, 'author_id' => $author_id, 'category_id' => $category_id]);
    }

    if ($method === 'DELETE') {
        $id = isset($body['id']) ? (int)$body['id'] : null;
        if ($id === null) respond(['message' => 'Missing Required Parameters']);
        if ($model->delete($id) === 0) respond(['message' => 'No Quotes Found']);
        respond(['id' => $id]);
    }

    http_response_code(405);
    respond(['message' => 'Method Not Allowed']);
}

function handleAuthors($db, $method, $params, $body) {
    $model = new Author($db);

    if ($method === 'GET') {
        $id   = isset($params['id']) ? (int)$params['id'] : null;
        $rows = $id !== null ? $model->getById($id) : $model->getAll();
        if (empty($rows)) respond(['message' => 'author_id Not Found']);
        respond(count($rows) === 1 ? $rows[0] : $rows);
    }

    if ($method === 'POST') {
        $author = isset($body['author']) ? trim($body['author']) : null;
        if (empty($author)) respond(['message' => 'Missing Required Parameters']);
        respond($model->create($author));
    }

    if ($method === 'PUT') {
        $id     = isset($body['id'])     ? (int)$body['id']      : null;
        $author = isset($body['author']) ? trim($body['author'])  : null;
        if (empty($author)) respond(['message' => 'Missing Required Parameters']);
        if ($model->update($id, $author) === 0) respond(['message' => 'author_id Not Found']);
        respond(['id' => $id, 'author' => $author]);
    }

    if ($method === 'DELETE') {
        $id = isset($body['id']) ? (int)$body['id'] : null;
        if ($id === null) respond(['message' => 'Missing Required Parameters']);
        if ($model->delete($id) === 0) respond(['message' => 'author_id Not Found']);
        respond(['id' => $id]);
    }

    http_response_code(405);
    respond(['message' => 'Method Not Allowed']);
}

function handleCategories($db, $method, $params, $body) {
    $model = new Category($db);

    if ($method === 'GET') {
        $id   = isset($params['id']) ? (int)$params['id'] : null;
        $rows = $id !== null ? $model->getById($id) : $model->getAll();
        if (empty($rows)) respond(['message' => 'category_id Not Found']);
        respond(count($rows) === 1 ? $rows[0] : $rows);
    }

    if ($method === 'POST') {
        $category = isset($body['category']) ? trim($body['category']) : null;
        if (empty($category)) respond(['message' => 'Missing Required Parameters']);
        respond($model->create($category));
    }

    if ($method === 'PUT') {
        $id       = isset($body['id'])       ? (int)$body['id']        : null;
        $category = isset($body['category']) ? trim($body['category'])  : null;
        if (empty($category)) respond(['message' => 'Missing Required Parameters']);
        if ($model->update($id, $category) === 0) respond(['message' => 'category_id Not Found']);
        respond(['id' => $id, 'category' => $category]);
    }

    if ($method === 'DELETE') {
        $id = isset($body['id']) ? (int)$body['id'] : null;
        if ($id === null) respond(['message' => 'Missing Required Parameters']);
        if ($model->delete($id) === 0) respond(['message' => 'category_id Not Found']);
        respond(['id' => $id]);
    }

    http_response_code(405);
    respond(['message' => 'Method Not Allowed']);
}
