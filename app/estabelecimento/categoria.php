<?php
// ************************************************
// CONFIGURAÇÕES INICIAIS E CARREGAMENTO DE RECURSOS
// ************************************************

// Carregando definições do sistema
include($virtualpath.'/_layout/define.php');

// Configurações globais da aplicação
global $app;
is_active($app['id']);
global $inparametro;
$back_button = "true";

// ************************************************
// CONSULTAS AO BANCO DE DADOS E PARÂMETROS
// ************************************************

// Identificadores e parâmetros de filtro
$app_id = $app['id'];
$busca = mysqli_real_escape_string($db_con, $_GET['busca']);
$categoria = $inparametro;
$filtro = mysqli_real_escape_string($db_con, $_GET['filtro']);

// Verificação de existência da categoria
$query_content = mysqli_query($db_con, "SELECT * FROM categorias WHERE rel_estabelecimentos_id = '$app_id' AND id = '$categoria' AND status = '1' ORDER BY ordem ASC");
$has_content = mysqli_num_rows($query_content);
if(!$categoria) {
    $has_content = "true"; // Categoria "todas" é sempre válida
}

// Construção dos parâmetros para URLs
$getdata = "?";
if($busca) {
    $getdata .= "busca=".$busca."&";
}
$getdata_unfilter = substr($getdata, 0, -1);
if($getdata_unfilter == "") {
    $getdata_unfilter = "?";
}
if($filtro) {
    $getdata .= "filtro=".$filtro."&";
}
$getdata = substr($getdata, 0, -1);

// ************************************************
// CONFIGURAÇÕES DE SEO (OTIMIZAÇÃO PARA BUScADORES)
// ************************************************

// Definição do nome da categoria para SEO
$categoria_nome = data_info("categorias", $categoria, "nome");
if(!$categoria_nome && $categoria) {
    $categoria_nome = "Categoria inválida";
} else {
    if(!$categoria_nome) {
        $categoria_nome = "Todas as categorias";
    }
}

// Personalização do título para filtros especiais
if($filtro == "4") {
    $categoria_nome .= " em oferta";
}
if($busca) {
    $categoria_nome = "Buscar";
}

// Configurações gerais de SEO
$seo_subtitle = $app['title']." - ".$categoria_nome;
$seo_description = $categoria_nome." ".$app['title']." no ".$seo_title;
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = thumber($app['avatar_clean'], 400);

// ************************************************
// INCLUSÃO DOS ARQUIVOS DE LAYOUT
// ************************************************

$system_header .= "";
include($virtualpath.'/_layout/head.php');
include($virtualpath.'/_layout/top.php');
include($virtualpath.'/_layout/sidebars.php');
include($virtualpath.'/_layout/modal.php');
instantrender();
?>

<div class="sceneElement">
    <!-- ************************************************ -->
    <!-- CABEÇALHO MOBILE COM LOGO DO ESTABELECIMENTO -->
    <!-- ************************************************ -->
    <div class="header-interna">
        <div class="locked-bar visible-xs visible-sm">
            <div class="avatar">
                <div class="holder">
                    <a href="<?php echo $app['url']; ?>">
                        <img src="<?php echo $app['avatar']; ?>"/>
                    </a>
                </div>    
            </div>
        </div>
        <div class="holder-interna visible-xs visible-sm"></div>
    </div>

    <div class="minfit">
        <?php if($has_content) { ?>
            <!-- ************************************************ -->
            <!-- CONTEÚDO QUANDO A CATEGORIA EXISTE -->
            <!-- ************************************************ -->
            <div class="middle">
                <div class="container">
                    <!-- TÍTULO E BREADCRUMB (CAMINHO DE NAVEGAÇÃO) -->
                    <div class="row rowtitle hidden-xs hidden-sm">
                        <?php 
                        if(!$busca) { 
                            // Exibição para navegação de categorias
                            $categoria_name = htmlclean(data_info("categorias", $categoria, "nome"));
                            if(!$categoria_name) { 
                                $categoria_name = "Geral"; 
                            }
                        ?>
                            <div class="col-md-12">
                                <div class="title-icon">
                                    <span><?php echo $categoria_name; ?></span>
                                </div>
                                <div class="bread-box">
                                    <div class="bread">
                                        <a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
                                        <span>/</span>
                                        <a href="<?php echo $app['url']; ?>/categoria/<?php echo $inparametro; ?><?php echo $getdata; ?>">Categorias</a>
                                        <span>/</span>
                                        <a href="<?php echo $app['url']; ?>/categoria/<?php echo $inparametro; ?><?php echo $getdata; ?>"><?php echo $categoria_name; ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <!-- Exibição para resultados de busca -->
                            <div class="col-md-12">
                                <div class="title-icon">
                                    <span>Buscar:</span>
                                </div>
                                <div class="bread-box">
                                    <div class="bread">
                                        <a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
                                        <span>/</span>
                                        <a href="<?php echo $app['url']; ?>/categoria/<?php echo $inparametro; ?><?php echo $getdata; ?>">Buscar: <u><?php echo htmlclean($busca); ?></u></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <div class="col-md-12 hidden-xs hidden-sm">
                            <div class="clearline"></div>
                        </div>
                    </div>

                    <!-- BARRA DE BUSCA PARA MOBILE -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="search-bar-mobile visible-xs visible-sm">
                                <form class="align-middle" method="GET">
                                    <input type="text" name="busca" placeholder="Digite sua busca..." value="<?php echo htmlclean($_GET['busca']); ?>"/>
                                    <input type="hidden" name="categoria" value="<?php echo $categoria; ?>"/>
                                    <button>
                                        <i class="lni lni-search-alt"></i>
                                    </button>
                                    <div class="clear"></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- MENU DE NAVEGAÇÃO ENTRE CATEGORIAS -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tv-infinite tv-infinite-menu">
                                <?php
                                // Preparação dos parâmetros para manter filtros ao trocar de categoria
                                if($busca) {
                                    $query_busca = "&busca=".$busca;
                                }
                                if($filtro) {
                                    $query_busca = "&filtro=".$filtro;
                                }
                                ?>
                                <a class="<?php if(!$categoria){ echo 'active'; }; ?>" href="<?php echo $app['url']; ?>/categoria?<?php echo $query_busca; ?>">Todas</a>
                                <?php        
                                // Listagem das categorias disponíveis
                                $query_categorias = mysqli_query($db_con, "SELECT * FROM categorias WHERE rel_estabelecimentos_id = '$app_id' AND visible = '1' AND status = '1' ORDER BY ordem ASC");
                                while($data_categoria = mysqli_fetch_array($query_categorias)) {
                                ?>
                                <a class="<?php if($data_categoria['id'] == $categoria){ echo 'active'; }; ?>" href="<?php echo $app['url']; ?>/categoria/<?php echo $data_categoria['id']; ?><?php if($query_busca) { echo "?".$query_busca; }; ?>"><?php echo $data_categoria['nome']; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- ************************************************ -->
                    <!-- LISTAGEM DE PRODUTOS DA CATEGORIA -->
                    <!-- ************************************************ -->
                    <div class="categorias no-bottom-mobile">
                        <?php
                        // Configurações de paginação
                        $limite = 12; // Produtos por página
                        $pagina = $_GET["pagina"] == "" ? 1 : $_GET["pagina"];
                        $inicio = ($pagina * $limite) - $limite;

                        // Construção da consulta SQL base
                        $query = "";
                        $query .= "SELECT * FROM produtos ";
                        $query .= "WHERE 1=1 ";

                        // Filtros aplicados à consulta
                        if($categoria) {
                          $query .= "AND rel_categorias_id = '$categoria' ";
                        }

                        if($busca) {
                          $query .= "AND nome LIKE '%$busca%' ";
                        }

                        $query .= "AND status = '1' AND visible = '1' ";
                        $query .= "AND rel_estabelecimentos_id = '$app_id' ";

                        $query_full = $query; // Versão completa para contagem

                        // Ordenação baseada no filtro selecionado
                        if($filtro == "1" OR !$filtro) {
                            // Ordenação por relevância (mais recentes primeiro)
                            $query .= "ORDER BY id DESC LIMIT $inicio,$limite";
                        }

                        if($filtro == "2") {
                            // Ordenação por preço crescente
                            $query .= "ORDER BY valor_promocional ASC LIMIT $inicio,$limite";
                        }

                        if($filtro == "3") {
                            // Ordenação por preço decrescente
                            $query .= "ORDER BY valor_promocional DESC LIMIT $inicio,$limite";
                        }

                        if($filtro == "4") {
                            // Filtro de ofertas
                            $query .= "AND oferta = '1' ";
                            $query .= "ORDER BY valor_promocional ASC LIMIT $inicio,$limite";
                        }

                        // Execução das consultas e cálculos de paginação
                        $sql = mysqli_query($db_con, $query);
                        $total_results = mysqli_num_rows($sql);
                        $sql_full = mysqli_query($db_con, $query_full);
                        $total_results_full = mysqli_num_rows($sql_full);
                        
                        // CORREÇÃO: Cálculo correto do total de páginas
                        $total_paginas = ceil($total_results_full / $limite);

                        // Validação da página atual
                        if(!$pagina || !is_numeric($pagina) || $pagina < 1) {
                            $pagina = 1;
                        } else if($pagina > $total_paginas && $total_paginas > 0) {
                            // Se a página solicitada for maior que o total, redirecionar para a última
                            header("Location: ".$app['url']."/categoria/".($inparametro ? $inparametro : "")."/?pagina=".$total_paginas.$getdata);
                            exit;
                        }
                        ?>

                        <div class="categoria no-bottom-mobile">
                            <!-- CABEÇALHO COM CONTADOR E FILTRO DE ORDENAÇÃO -->
                            <div class="row">
                                <!-- Contador de itens encontrados -->
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <strong class="counter"><?php echo $total_results_full; ?></strong>
                                    <span class="title">Itens:</span>
                                </div>
                                
                                <!-- Seletor de filtros/ordenação -->
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="filter-select pull-right">
                                        <i class="outside lni lni-funnel"></i>
                                        <div class="fake-select">
                                            <i class="lni lni-chevron-down"></i>
                                            <select name="filtro" onchange="selecturl()">
                                                <option <?php if($_GET['filtro'] == "1") { echo "SELECTED"; }; ?> value="<?php echo $app['url']; ?>/categoria/<?php echo $inparametro; ?><?php echo $getdata_unfilter; ?>&filtro=1">Relevância</option>
                                                <option <?php if($_GET['filtro'] == "2") { echo "SELECTED"; }; ?> value="<?php echo $app['url']; ?>/categoria/<?php echo $inparametro; ?><?php echo $getdata_unfilter; ?>&filtro=2">Preço <</option>
                                                <option <?php if($_GET['filtro'] == "3") { echo "SELECTED"; }; ?> value="<?php echo $app['url']; ?>/categoria/<?php echo $inparametro; ?><?php echo $getdata_unfilter; ?>&filtro=3">Preço ></option>
                                                <option <?php if($_GET['filtro'] == "4") { echo "SELECTED"; }; ?> value="<?php echo $app['url']; ?>/categoria/<?php echo $inparametro; ?><?php echo $getdata_unfilter; ?>&filtro=4">Ofertas</option>
                                            </select>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- GRID DE PRODUTOS -->
                            <div class="produtos">
                                <div class="row tv-grid">
                                    <?php
                                    // Se não houver produtos nesta página, exibir mensagem
                                    if($total_results == 0) {
                                        echo '<div class="col-md-12 text-center"><p style="padding: 20px;">Nenhum produto encontrado nesta categoria.</p></div>';
                                    }
                                    
                                    $count_produtos = 0; // Contador para validar número de produtos exibidos
                                    
                                    while($data_produtos = mysqli_fetch_array($sql)) {
                                        // Verificação completa para produtos com dados incompletos
                                        if(!$data_produtos['nome'] || !$data_produtos['id'] || !$data_produtos['destaque']) {
                                            error_log("Produto incompleto: ID={$data_produtos['id']}, Nome={$data_produtos['nome']}, Destaque={$data_produtos['destaque']}");
                                            continue; // Pula produtos com dados incompletos
                                        }
                                        
                                        $count_produtos++; // Incrementa contador de produtos válidos
                                        
                                        // Define o valor final (oferta ou preço normal)
                                        $valor_final = ($data_produtos['oferta'] == "1") ? $data_produtos['valor_promocional'] : $data_produtos['valor'];
                                        
                                        // Garante que o valor_final é um número válido
                                        $valor_final = is_numeric($valor_final) ? $valor_final : 0;
                                    ?>
                                    <div class="col-md-3 col-sm-6 col-xs-6">
                                        <div class="produto">
                                            <a href="<?php echo $app['url']; ?>/produto/<?php echo $data_produtos['id']; ?>" title="<?php echo $data_produtos['nome']; ?>">
                                                <!-- Imagem do produto com fallback para imagens não disponíveis -->
                                                <div class="capa" style="background-image: url(<?php echo thumber($data_produtos['destaque'] ? $data_produtos['destaque'] : 'assets/img/no-image.jpg', 450); ?>);"></div>
                                                <span class="nome"><?php echo htmlclean($data_produtos['nome']); ?></span>
                                                
                                                <?php if($valor_final > 0) { ?>
                                                    <?php if($data_produtos['oferta'] == "1" && $data_produtos['valor'] > 0) { ?>
                                                        <!-- Exibição de preço promocional com valor original riscado -->
                                                        <span class="valor_anterior">De: <?php echo dinheiro($data_produtos['valor'], "BR"); ?></span>
                                                    <?php } else { ?>
                                                        <span class="valor_anterior invisible">&nbsp;</span>
                                                    <?php } ?>
                                                    <span class="apenas">Por apenas</span>
                                                    <span class="valor">R$ <?php echo dinheiro($valor_final, "BR"); ?></span>
                                                    
                                                    <!-- Verificação de disponibilidade em estoque -->
                                                    <?php if($data_produtos['estoque'] == 1 || ($data_produtos['estoque'] == 2 && $data_produtos['posicao'] > 0)) { ?>
                                                        <div class="detalhes"><i class="icone icone-sacola"></i> <span>Comprar</span></div>
                                                    <?php } else { ?>
                                                        <div class="detalhes sem-estoque"><i class="lni lni-close"></i> <span>Sem Estoque</span></div>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <!-- Produto com preço variável/opcionais -->
                                                    <span class="apenas">Este item possui</span>
                                                    <span class="apenas">opcionais</span>
                                                    <span class="valor" style="color:#FFFFFF">.</span>
                                                    <div class="detalhes"><i class="icone icone-sacola"></i> <span>Selecione</span></div>
                                                <?php } ?>
                                            </a>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    
                                    <?php 
                                    // Log para diagnóstico
                                    if($count_produtos != $total_results) {
                                        error_log("Discrepância na contagem de produtos: Esperado {$total_results}, Exibido {$count_produtos}");
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ************************************************ -->
                    <!-- CONTROLES DE PAGINAÇÃO -->
                    <!-- ************************************************ -->
                    <?php if($total_paginas > 1) { ?>
                    <div class="paginacao">
                        <ul class="pagination">
                            <?php
                            // Construção correta do path de paginação
                            $paginationpath = "categoria";
                            if($inparametro) {
                                $paginationpath .= "/".$inparametro;
                            }
                            
                            // Botão para página anterior
                            if($pagina > 1) {
                                $back = $pagina-1;
                                echo '<li class="page-item pagination-back"><a class="page-link" href="'.$app['url'].'/'.$paginationpath.'?pagina='.$back.($getdata != "?" ? $getdata : "").'"><i class="lni lni-chevron-left"></i></a></li>';
                            }
                     
                            // Exibição da página anterior à atual
                            if($pagina > 1) {
                                echo '<li class="page-item pages-before"><a class="page-link" href="'.$app['url'].'/'.$paginationpath.'?pagina='.($pagina-1).($getdata != "?" ? $getdata : "").'">' . ($pagina-1) . '</a></li>';
                            }

                            // Exibição da página atual
                            echo '<li class="page-item active"><a class="page-link" href="'.$app['url'].'/'.$paginationpath.'?pagina='.$pagina.($getdata != "?" ? $getdata : "").'">' . $pagina . '</a></li>';

                            // Exibição da página posterior à atual
                            if($pagina < $total_paginas) {
                                echo '<li class="page-item pages-after"><a class="page-link" href="'.$app['url'].'/'.$paginationpath.'?pagina='.($pagina+1).($getdata != "?" ? $getdata : "").'">' . ($pagina+1) . '</a></li>';
                            }

                            // Botão para próxima página
                            if($pagina < $total_paginas) {
                                $next = $pagina+1;
                                echo '<li class="page-item pagination-next"><a class="page-link" href="'.$app['url'].'/'.$paginationpath.'?pagina='.$next.($getdata != "?" ? $getdata : "").'"><i class="lni lni-chevron-right"></i></a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>
        <?php } else { ?>
            <!-- ************************************************ -->
            <!-- MENSAGEM PARA CATEGORIA INVÁLIDA -->
            <!-- ************************************************ -->
            <div class="middle">
                <div class="container">
                    <div class="row">
                        <span class="nulled nulled-content">Categoria inválida ou removida!</span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php 
// ************************************************
// CARREGAMENTO DO RODAPÉ
// ************************************************
$system_footer .= "";
include($virtualpath.'/_layout/rdp.php');
include($virtualpath.'/_layout/footer.php');
?>

<!-- ************************************************ -->
<!-- SCRIPTS JAVASCRIPT -->
<!-- ************************************************ -->
<script>
// Centraliza a categoria ativa no menu horizontal
$(document).ready(function(){
    var active = $(".tv-infinite-menu .active");
    var activeWidth = active.width();
    // var pos = active.position().left + activeWidth;
    var pos = active.position().left-15;
    $('.tv-infinite-menu').animate({ scrollLeft: pos }, 500);
});

// Função para redirecionamento ao selecionar um filtro
$("select[name='filtro']").change(function(){
    var theurl = $(this).children("option:selected").val();
    window.location.href = theurl;
});
</script>