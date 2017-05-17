<?php

// Routes
$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
$app->get('/employees/', function ($request, $response, $args) {
    //obtener contenido del archivo employees.json
    $data = file_get_contents(__DIR__ . '\employees.json');
    $data = json_decode($data, TRUE);
    $data_jason = array();
    //cambiar id del array por el id del empleado
    foreach ($data as $k => $v) {
        $data_jason[$data[$k]['id']] = $v;
    }
    //se genera la ruta del generador de archivo xml con valores por defecto
    $xml = $this->router->pathFor('xml', array('min' => '0', 'max' => '1'));
    return $this->renderer->render($response, 'employees.phtml', [
                'listado' => $data_jason,
                'ruta' => $xml
    ]);
});
$app->get('/file/xml/{min}/{max}', function ($request, $response, $args) use ($app) {
    //obtener contenido del archivo employees.json
    $data = file_get_contents(__DIR__ . '\employees.json');
    $data = json_decode($data, TRUE);
    $data_jason = array();
    foreach ($data as $k => $v) {
        //cambiar id del array por el id del empleado
        $data_jason[$data[$k]['id']] = $v;
        //se reemplaza el salario a un formato de numeros reconocible
        $salary = (str_replace(array('$', ','), '', $data_jason[$data[$k]['id']]['salary']));
        $salary = number_format($salary, 0, ',', '');
        $data_jason[$data[$k]['id']]['salary'] = $salary;
        $skills = '';
        //se une todos los skills del empleado en un solo campo
        foreach ($data_jason[$data[$k]['id']]['skills'] as $key => $value) {
            $skills.= ($skills === '') ? $value['skill'] : ', ' . $value['skill'];
        }
        $data_jason[$data[$k]['id']]['skills'] = $skills;
    }
    //se valida si no esta dentro del rango de salario minimo y maximo se remueve del array
    foreach ($data_jason as $k => $v) {
        if ((int) $data_jason[$k]['salary'] >= (int) $args['min'] && (int) $data_jason[$k]['salary'] <= (int) $args['max']) {
            
        } else {
            unset($data_jason[$k]);
        }
    }
    //se carga la plantillaxml
    $library = new SimpleXMLElement(__DIR__ . '/file_plantilla.xml', null, true);
    foreach ($data_jason as $k => $v) {
        // Primero creamos un elemento <employee> y lo agregamos al elemento raíz <library>
        $book = $library->addChild('employee');
        // Le asignamos el atributo [id] al elemento <employee>
        $book->addAttribute('id', $data_jason[$k]['id']);
        // Creamos los elementos que van dentro de <employee>: <name>, <email>...
        $book->addChild('name', $data_jason[$k]['name']);
        $book->addChild('email', $data_jason[$k]['email']);
        $book->addChild('phone', $data_jason[$k]['phone']);
        $book->addChild('address', $data_jason[$k]['address']);
        $book->addChild('position', $data_jason[$k]['position']);
        $book->addChild('salary', $data_jason[$k]['salary']);
        $book->addChild('salary', $data_jason[$k]['salary']);
        $book->addChild('skills', $data_jason[$k]['skills']);
    }
    //se crea o reemplaza el archivo file.xml
    $library->asXML(__DIR__ . '/file.xml');
    $file = __DIR__ . '/file.xml';
    $fh = fopen($file, 'rb');
    $stream = new \Slim\Http\Stream($fh);
    //se  devuelve el archivo con los datos de los empleados dentro del rango de salario minimo y maximo
    return $response->withHeader('Content-Type', 'application/force-download')
                    ->withHeader('Content-Type', 'application/octet-stream')
                    ->withHeader('Content-Type', 'application/download')
                    ->withHeader('Content-Description', 'File Transfer')
                    ->withHeader('Content-Transfer-Encoding', 'binary')
                    ->withHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"')
                    ->withHeader('Expires', '0')
                    ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                    ->withHeader('Pragma', 'public')
                    ->withBody($stream);
})->setName('xml');
