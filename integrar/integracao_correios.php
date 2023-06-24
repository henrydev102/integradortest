<?php

// Aqui é realizada a requisição à API dos correios
function requestCorreiosAPI($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// Coloquei os dados de conexão com o banco de dados
$host = '[localhost]'; // endereço do servidor
$dbName = '[test]'; // nome do banco de dados
$username = '[root]'; // nome de usuári
$password = '[]'; // senha

try {
    // Criei a conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);

    // Defini o modo de erro do PDO para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Aqui está a URL da API dos Correios para consultar cidades
    $url = 'https://cws.correios.com.br/cidades/cidadesService.php?wsdl';

    // Aqui é realizada a requisição à API dos Correios
    $response = requestCorreiosAPI($url);

    // Aqui eu processo a resposta da API
    $arrayResponse = json_decode(json_encode(simplexml_load_string($response)), true);

    // Verifiquei se a resposta da API é válida e contém os dados esperados
    if (isset($arrayResponse['cidades'])) {
        // Obtém as cidades da resposta
        $cidades = $arrayResponse['cidades'];

        // Defini a estrutura da tabela 'countries'
        $createCountriesTable = "
            CREATE TABLE countries (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            );
        ";

        // Defini a estrutura da tabela 'cities'
        $createCitiesTable = "
            CREATE TABLE cities (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                country_id INT NOT NULL,
                FOREIGN KEY (country_id) REFERENCES countries(id)
            );
        ";

        // Executei os comandos SQL para criar as tabelas
        $pdo->exec($createCountriesTable);
        $pdo->exec($createCitiesTable);

        // Salva as cidades no banco de dados
        foreach ($cidades as $cidade) {
            $nomeCidade = $cidade['nome'];
            $idPais = $cidade['idPais'];

            $stmt = $pdo->prepare("INSERT INTO cities (name, country_id) VALUES (?, ?)");
            $stmt->execute([$nomeCidade, $idPais]);
        }

        // mensagem de sucesso :)
        echo "Cidades salvas no banco de dados com sucesso!";
    } else {
        // mensagem de erro :(
        echo "Resposta inválida da API dos Correios.";
    }

} catch (PDOException $e) {
    // Trata o erro exibindo uma mensagem ou registrando em um log
    echo "Erro: " . $e->getMessage();
    // Ou então utiliza um sistema de log para registrar o erro
}

require 'apirest.php';
?>
