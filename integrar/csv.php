<?php
// Aqui estão os dados de conexão com o banco de dados
$host = '[localhost]'; // Esse é o endereço do servidor do banco de dados
$dbName = '[test]'; // O nome do banco
$username = '[root]'; // Nome de usuário
$password = '[]'; // Senha

try {
    // Aqui cria a conexão com o banco utilizando o PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);

    // Aqui defino o modo de erro do PDO para exceções 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Aqui eu defino a estrutura da tabela de 'dados'
    $createTableQuery = "
        CREATE TABLE dados (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            idade INT NOT NULL
        );
    ";

    // Execução do comando SQL para criar a tabela 

    $pdo->exec($createTableQuery);

    echo "Tabela criada com sucesso!";
} catch (PDOException $e) {
    echo "Houve um erro ao criar a tabela: " . $e->getMessage();
}

// Arquivo CSV
$csvFile = 'integrar/Slice-teste-dev-integrador.csv';

try {
    // Abre o arquivo CSV para leitura
    if (($handle = fopen($csvFile, 'r')) !== false) {
        // Ignora a primeira linha (cabeçalho)
        fgetcsv($handle);

        // Lê o conteúdo do arquivo linha por linha
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {

            
            $nome = $data[0];
            $idade = $data[1];

            $stmt = $pdo->prepare("INSERT INTO dados (nome, idade) VALUES (?, ?)");
            $stmt->execute([$nome, $idade]);
        }

        fclose($handle);

        echo "Dados inseridos no banco de dados com sucesso!";
    } else {
        echo "Erro ao abrir o arquivo CSV.";
    }
} catch (PDOException $e) {
    echo "Erro ao colocar os dados: " . $e->getMessage();
}
?>