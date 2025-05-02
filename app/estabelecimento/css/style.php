<?php
header("Content-type: text/css");
include('../../../_core/_includes/config.php');

// Busca a cor do estabelecimento
$id = mysqli_real_escape_string($db_con, $_GET['id']);
$define_query = mysqli_query($db_con, "SELECT cor FROM estabelecimentos WHERE id = '$id' LIMIT 1");
$define_data = mysqli_fetch_array($define_query);
$cor = $define_data['cor'];

// Cor padrão caso não exista
if(!$cor) {
    $cor = "#27293E";
}
?>

/* ===================================
   ELEMENTOS COM COR PERSONALIZADA
   =================================== */

/* Elementos com a cor como texto */
.colored,
.shop-bag i,
.naver .navbar a i,
.header .naver .navbar .social a:hover i,
.naver .navbar a:hover,
.user-menu i,
.search-bar-mobile button i,
.categoria .vertudo i,
.categoria .counter,
.bread i,
.produto-detalhes .categoria a,
.campo-numero i,
.sacola-table .sacola-remover i,
.sacola-table .sacola-change i,
.adicionado .checkicon,
.title-line i,
.back-button i,
.sidebar-info i,
.filter-select .outside,
.filter-select .fake-select i,
.pagination i,
.funcionamento-mobile i,
.fake-select i,
.search-bar button i,
.holder-shop-bag i {
    color: <?php echo $cor; ?> !important;
}

/* Elementos com a cor como borda */
.top {
    border-color: <?php echo $cor; ?> !important;
}

.tv-infinite-menu a.active,
.tv-infinite-menu a:hover,
.fancybox-thumbs__list a::before {
    border-color: <?php echo $cor; ?> !important;
}

/* Elementos com a cor como background */
.footer-info,
.categoria .produto .detalhes,
.cover,
.carousel-indicators .active,
.botao-acao,
.sidebar .sidebar-header,
.minitop,
.opcoes .opcao.active .check,
.floatbar {
    background: <?php echo $cor; ?> !important;
}

/* Paginação com a cor personalizada */
.pagination > li > a:hover, 
.pagination > .active > a, 
.pagination > .active > a:focus, 
.pagination > .active > a:hover, 
.pagination > .active > span, 
.pagination > .active > span:focus, 
.pagination > .active > span:hover {
    background: <?php echo $cor; ?> !important;
    color: #fff !important;
}

/* ===================================
   ESTILOS FIXOS
   =================================== */

/* Avatar fixo */
.is-sticky .avatar {
    height: 70px !important;
    width: 70px !important;
}

/* Classes de visibilidade */
.invisible {
    visibility: hidden !important;
}

/* ===================================
   ESTILOS DOS PRODUTOS
   =================================== */

/* Imagem do produto */
.produto .capa {
    background-repeat: no-repeat;
    background-position: center center;
    height: 250px;
    width: 100%;
    background-size: cover;
}

/* Nome do produto */
.produto .nome {
    color: #333;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Open Sans', Arial, sans-serif;
    letter-spacing: -0.2px;
    display: block;
    margin-top: 10px;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Valor anterior (riscado) */
.produto .valor_anterior {
    color: #FF0000 !important;
    display: block;
    text-align: left;
    margin-bottom: 3px;
    min-height: 16px;
    line-height: 1.2;
}

/* Valor anterior invisível */
.produto .valor_anterior.invisible {
    visibility: hidden !important;
    color: transparent !important;
}

/* Texto "Por apenas" */
.produto .apenas {
    white-space: nowrap;
    display: block;
    text-align: left;
    margin-bottom: 5px;
    line-height: 1.2;
    height: 20px;
}

/* Valor do produto */
.produto .valor {
    display: block;
    text-align: center;
    font-weight: bold;
    margin-bottom: 5px;
}

/* Botão de detalhes */
.produto .detalhes {
    padding: 10px 0;
    text-align: center;
    color: #fff;
}

/* Estilo para sem estoque */
.produto .detalhes.sem-estoque {
    background-color: #C0C0C0 !important;
}

/* Dimensão consistente da coluna */
.col-infinite {
    min-height: 420px;
}

/* ===================================
   MEDIA QUERIES
   =================================== */

/* MOBILE */
@media (max-width: 991px) {
    .user-menu i {
        color: #fff !important;
    }
    
    .shop-bag i {
        color: #fff !important;
    }
    
    .shop-bag .counter {
        border: 0;
        padding-top: 2px;
    }
    
    .top {
        border-top: 0;
        background: <?php echo $cor; ?> !important;
    }
}

/* DESKTOP */
@media (min-width: 991px) {
    /* Estilos específicos para desktop */
}