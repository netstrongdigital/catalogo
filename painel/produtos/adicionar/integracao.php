<?php
include('../../../_core/_includes/config.php');
include('../../../_core/_includes/simple_html_dom.php'); // Inclui a biblioteca Simple HTML DOM
session_start();

// Função para carregar HTML usando cURL com mais configurações robustas
function file_get_html_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    
    // Adiciona headers para parecer mais com um navegador real
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
        'Connection: keep-alive',
        'Upgrade-Insecure-Requests: 1',
        'Cache-Control: max-age=0'
    ]);
    
    $html = curl_exec($ch);
    
    if (curl_errno($ch)) {
        error_log("cURL Error: " . curl_error($ch));
        curl_close($ch);
        return null;
    }
    
    curl_close($ch);
    
    // Verifica se a resposta HTML está vazia
    if (empty($html)) {
        error_log("HTML vazio retornado para URL: $url");
        return null;
    }
    
    return str_get_html($html);
}

// Função para obter seletores corretos com base na plataforma
function getPlatformSelectors($platform) {
    $selectors = [
        'shopee' => [
            'nome' => 'div.qaNIZv span[data-testid="product-name"]',
            'descricao' => 'div[data-testid="pdp-product-description-content"]',
            'preco' => 'div.pqTWkA div[data-testid="pdp-product-price"]',
            'imagens_container' => 'div[data-testid="pdp-main-image-container"]',
            'imagem' => 'img',
            'variacoes_container' => 'div[data-testid="pdp-option-selector"]',
            'variacao' => 'div.B-8A4s'
        ],
        'mercadolivre' => [
            'nome' => 'h1.ui-pdp-title',
            'descricao' => 'div.ui-pdp-description__content',
            'preco' => 'span.andes-money-amount__fraction',
            'preco_cents' => 'span.andes-money-amount__cents',
            'imagens_container' => 'figure.ui-pdp-gallery__figure',
            'imagem' => 'img.ui-pdp-image',
            'variacoes_container' => 'div.ui-pdp-variations',
            'variacao' => 'a.ui-pdp-variations__selected-option, span.ui-pdp-variations__selected-value'
        ]
    ];
    
    return isset($selectors[$platform]) ? $selectors[$platform] : null;
}

// Função para extrair informações do produto
function extractProductInfo($platform, $productLink) {
    $html = file_get_html_curl($productLink);
    
    if (!$html) {
        error_log("Erro ao carregar o HTML da página: $productLink");
        return null;
    }
    
    $selectors = getPlatformSelectors($platform);
    
    if (!$selectors) {
        error_log("Plataforma não suportada: $platform");
        return null;
    }
    
    try {
        if ($platform === 'shopee') {
            return extractShopeeProductInfo($html, $selectors);
        } elseif ($platform === 'mercadolivre') {
            return extractMercadoLivreProductInfo($html, $selectors);
        }
    } catch (Exception $e) {
        error_log("Erro ao extrair informações: " . $e->getMessage());
        return null;
    }
    
    return null;
}

// Função específica para extrair informações da Shopee
function extractShopeeProductInfo($html, $selectors) {
    // Inicializa com valores padrão
    $productInfo = [
        'nome' => 'Nome não encontrado',
        'descricao' => 'Descrição não encontrada',
        'preco' => '0.00',
        'imagens' => [],
        'variacoes' => []
    ];
    
    // Extrai o nome do produto
    $nomeElement = $html->find($selectors['nome'], 0);
    if ($nomeElement) {
        $productInfo['nome'] = trim($nomeElement->plaintext);
    }
    
    // Extrai a descrição do produto
    $descricaoElement = $html->find($selectors['descricao'], 0);
    if ($descricaoElement) {
        $productInfo['descricao'] = trim($descricaoElement->plaintext);
    }
    
    // Extrai o preço do produto
    $precoElement = $html->find($selectors['preco'], 0);
    if ($precoElement) {
        $preco = $precoElement->plaintext;
        $preco = preg_replace('/[^0-9,.]/', '', $preco);
        $preco = str_replace(',', '.', $preco); // Converte vírgula para ponto
        $productInfo['preco'] = $preco;
    }
    
    // Extrai as imagens
    $imagesContainer = $html->find($selectors['imagens_container'], 0);
    if ($imagesContainer) {
        foreach ($imagesContainer->find($selectors['imagem']) as $img) {
            if (isset($img->src) && !empty($img->src)) {
                $productInfo['imagens'][] = $img->src;
            } else if (isset($img->{'data-src'}) && !empty($img->{'data-src'})) {
                $productInfo['imagens'][] = $img->{'data-src'};
            }
        }
    }
    
    // Alternativa para imagens (caso o selector original falhe)
    if (empty($productInfo['imagens'])) {
        foreach ($html->find('img') as $img) {
            if (isset($img->src) && strpos($img->src, 'product') !== false) {
                $productInfo['imagens'][] = $img->src;
            }
        }
    }
    
    // Extrai as variações (se houver)
    $variacoesContainer = $html->find($selectors['variacoes_container'], 0);
    if ($variacoesContainer) {
        foreach ($variacoesContainer->find($selectors['variacao']) as $variation) {
            $productInfo['variacoes'][] = trim($variation->plaintext);
        }
    }
    
    // Limita o número de imagens para evitar problemas com dados muito grandes
    if (count($productInfo['imagens']) > 5) {
        $productInfo['imagens'] = array_slice($productInfo['imagens'], 0, 5);
    }
    
    return $productInfo;
}

// Função específica para extrair informações do Mercado Livre
function extractMercadoLivreProductInfo($html, $selectors) {
    // Inicializa com valores padrão
    $productInfo = [
        'nome' => 'Nome não encontrado',
        'descricao' => 'Descrição não encontrada',
        'preco' => '0.00',
        'imagens' => [],
        'variacoes' => []
    ];
    
    // Extrai o nome do produto
    $nomeElement = $html->find($selectors['nome'], 0);
    if ($nomeElement) {
        $productInfo['nome'] = trim($nomeElement->plaintext);
    }
    
    // Extrai a descrição do produto
    $descricaoElement = $html->find($selectors['descricao'], 0);
    if ($descricaoElement) {
        $productInfo['descricao'] = trim($descricaoElement->plaintext);
    }
    
    // Extrai o preço do produto (parte inteira)
    $precoElement = $html->find($selectors['preco'], 0);
    if ($precoElement) {
        $preco = trim($precoElement->plaintext);
        
        // Tenta obter os centavos, se disponíveis
        $precoCentsElement = $html->find($selectors['preco_cents'], 0);
        if ($precoCentsElement) {
            $cents = trim($precoCentsElement->plaintext);
            $preco = $preco . '.' . $cents;
        }
        
        $preco = preg_replace('/[^0-9,.]/', '', $preco);
        $preco = str_replace(',', '.', $preco); // Converte vírgula para ponto
        $productInfo['preco'] = $preco;
    }
    
    // Extrai as imagens
    foreach ($html->find($selectors['imagens_container']) as $figure) {
        $img = $figure->find($selectors['imagem'], 0);
        if ($img) {
            if (isset($img->src) && !empty($img->src)) {
                $productInfo['imagens'][] = $img->src;
            } else if (isset($img->{'data-src'}) && !empty($img->{'data-src'})) {
                $productInfo['imagens'][] = $img->{'data-src'};
            }
        }
    }
    
    // Alternativa para imagens (caso o selector original falhe)
    if (empty($productInfo['imagens'])) {
        foreach ($html->find('img.ui-pdp-image') as $img) {
            if (isset($img->src) && !empty($img->src)) {
                $productInfo['imagens'][] = $img->src;
            }
        }
    }
    
    // Extrai as variações (se houver)
    $variacoesContainer = $html->find($selectors['variacoes_container'], 0);
    if ($variacoesContainer) {
        foreach ($variacoesContainer->find($selectors['variacao']) as $variation) {
            $productInfo['variacoes'][] = trim($variation->plaintext);
        }
    }
    
    // Limita o número de imagens para evitar problemas com dados muito grandes
    if (count($productInfo['imagens']) > 5) {
        $productInfo['imagens'] = array_slice($productInfo['imagens'], 0, 5);
    }
    
    return $productInfo;
}

// Função para verificar se uma coluna existe na tabela
function columnExists($db_con, $table, $column) {
    $query = "SHOW COLUMNS FROM $table LIKE '$column'";
    $result = mysqli_query($db_con, $query);
    return mysqli_num_rows($result) > 0;
}

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Dados de entrada inválidos']);
        exit;
    }
    
    // Verifica se todos os campos necessários estão presentes
    if (!isset($data['platform']) || !isset($data['productLink']) || !isset($data['category'])) {
        echo json_encode(['success' => false, 'message' => 'Dados incompletos. Plataforma, link do produto e categoria são obrigatórios']);
        exit;
    }
    
    $platform = $data['platform'];
    $productLink = $data['productLink'];
    $category = $data['category'];
    
    // Verifica se a plataforma é suportada
    if ($platform !== 'shopee' && $platform !== 'mercadolivre') {
        echo json_encode(['success' => false, 'message' => 'Plataforma não suportada']);
        exit;
    }
    
    // Verifica se o estabelecimento está na sessão
    if (!isset($_SESSION['estabelecimento']) || !isset($_SESSION['estabelecimento']['id'])) {
        echo json_encode(['success' => false, 'message' => 'Estabelecimento não encontrado na sessão']);
        exit;
    }
    
    try {
        // Extrai as informações do produto
        $productInfo = extractProductInfo($platform, $productLink);
        
        if (!$productInfo) {
            echo json_encode(['success' => false, 'message' => 'Erro ao extrair informações do produto. Verifique o link e tente novamente.']);
            exit;
        }
        
        // Prepara os dados para salvar no banco de dados
        $nome = mysqli_real_escape_string($db_con, $productInfo['nome']);
        $descricao = mysqli_real_escape_string($db_con, $productInfo['descricao']);
        $preco = mysqli_real_escape_string($db_con, $productInfo['preco']);
        
        // Verifica se pelo menos uma imagem foi encontrada
        if (empty($productInfo['imagens'])) {
            error_log("Nenhuma imagem encontrada para o produto: $productLink");
            // Continua mesmo sem imagens
        }
        
        $imagens_json = mysqli_real_escape_string($db_con, json_encode($productInfo['imagens']));
        $variacoes_json = mysqli_real_escape_string($db_con, json_encode($productInfo['variacoes']));
        $rel_categorias_id = mysqli_real_escape_string($db_con, $category);
        $rel_estabelecimentos_id = mysqli_real_escape_string($db_con, $_SESSION['estabelecimento']['id']);
        $status = '1'; // Status ativo
        $created = date('Y-m-d H:i:s'); // Data de criação
        
        // Verifica se a coluna 'imagens' existe na tabela 'produtos'
        $imageColumnExists = columnExists($db_con, 'produtos', 'imagens');
        
        // Decide como armazenar as imagens com base na coluna disponível
        $imgColumn = $imageColumnExists ? 'imagens' : 'imagem';
        
        // Ajusta o valor de imagens conforme a coluna disponível
        $imgValue = $imageColumnExists ? 
            $imagens_json : 
            (count($productInfo['imagens']) > 0 ? mysqli_real_escape_string($db_con, $productInfo['imagens'][0]) : '');
        
        // Monta a consulta SQL (ajustada para a coluna de imagem correta)
        $query = "INSERT INTO produtos (
            rel_estabelecimentos_id,
            rel_categorias_id,
            nome,
            descricao,
            valor,
            variacao,
            $imgColumn,
            status,
            created
        ) VALUES (
            '$rel_estabelecimentos_id',
            '$rel_categorias_id',
            '$nome',
            '$descricao',
            '$preco',
            '$variacoes_json',
            '$imgValue',
            '$status',
            '$created'
        )";
        
        error_log("Consulta SQL: " . $query); // Exibe a consulta no log de erros
        
        // Executa a consulta
        if (mysqli_query($db_con, $query)) {
            $product_id = mysqli_insert_id($db_con);
            
            // Se temos várias imagens, mas só a coluna imagem (sem coluna imagens),
            // podemos inserir as imagens extras em uma tabela de imagens separada, se existir
            if (!$imageColumnExists && count($productInfo['imagens']) > 1) {
                if (columnExists($db_con, 'produtos_imagens', 'rel_produtos_id')) {
                    for ($i = 1; $i < count($productInfo['imagens']); $i++) {
                        $img_url = mysqli_real_escape_string($db_con, $productInfo['imagens'][$i]);
                        $img_query = "INSERT INTO produtos_imagens (rel_produtos_id, imagem, status) 
                                    VALUES ('$product_id', '$img_url', '1')";
                        mysqli_query($db_con, $img_query);
                    }
                }
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Produto adicionado com sucesso',
                'product_id' => $product_id
            ]);
        } else {
            error_log("Erro ao salvar no banco de dados: " . mysqli_error($db_con));
            echo json_encode([
                'success' => false, 
                'message' => 'Erro ao salvar no banco de dados: ' . mysqli_error($db_con)
            ]);
        }
    } catch (Exception $e) {
        error_log("Exceção capturada: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Erro ao adicionar produto: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>
