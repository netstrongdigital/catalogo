<?php
// CORE
include('../../../_core/_includes/config.php');
session_start();
// RESTRICT
restrict_estabelecimento();
restrict_limite( $_SESSION['estabelecimento']['id'] );
// SEO
$seo_subtitle = "Adicionar produto";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../../_layout/head.php');
include('../../_layout/top.php');
include('../../_layout/sidebars.php');
include('../../_layout/modal.php');
?>

<?php

  // Globals

  global $numeric_data;
  global $gallery_max_files;
  $eid = $_SESSION['estabelecimento']['id'];

  // Checar se formulário foi executado

  
  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

    $video_link = mysqli_real_escape_string( $db_con, $_POST['video_link'] );
    $categoria = mysqli_real_escape_string( $db_con, $_POST['categoria'] );
    $ref = mysqli_real_escape_string( $db_con, $_POST['ref'] );
    $nome = mysqli_real_escape_string( $db_con, $_POST['nome'] );
    $descricao = mysqli_real_escape_string( $db_con, $_POST['descricao'] );
    $estoque = mysqli_real_escape_string( $db_con, $_POST['estoque'] );
    $posicao = mysqli_real_escape_string( $db_con, $_POST['posicao'] );
    $valor = dinheiro( mysqli_real_escape_string( $db_con, $_POST['valor'] ) );
    if( !$valor ) {
      $valor = "0.00";
    }
    $oferta = mysqli_real_escape_string( $db_con, $_POST['oferta'] );
    $valor_promocional = dinheiro( mysqli_real_escape_string( $db_con, $_POST['valor_promocional'] ) );
    if( !$valor_promocional ) {
      $valor_promocional = "0.00";
    }
    $medidas = [];
    $medidas['altura'] = mysqli_real_escape_string( $db_con, $_POST['altura'] );
    $medidas['largura'] = mysqli_real_escape_string( $db_con, $_POST['largura'] );
    $medidas['comprimento'] = mysqli_real_escape_string( $db_con, $_POST['comprimento'] );
    $medidas['peso'] = dinheiro(mysqli_real_escape_string( $db_con, $_POST['peso'] ));

    $variacao =  $_POST['variacao'];
    for ( $x=0; $x < count( $variacao ); $x++ ){
      $variacao[$x]['nome'] = jsonsave( $variacao[$x]['nome'] );
      $variacao[$x]['escolha_minima'] = jsonsave( $variacao[$x]['escolha_minima'] );
      $variacao[$x]['escolha_maxima'] = jsonsave( $variacao[$x]['escolha_maxima'] );
      for( $y=0; $y < count( $variacao[$x]['item'] ); $y++ ){
        $variacao[$x]['item'][$y]['nome'] =  jsonsave( $variacao[$x]['item'][$y]['nome'] );
        $variacao[$x]['item'][$y]['descricao'] =  jsonsave( $variacao[$x]['item'][$y]['descricao'] );
        $variacao[$x]['item'][$y]['valor'] = jsonsave( dinheiro( mysqli_real_escape_string( $db_con,$variacao[$x]['item'][$y]['valor'] ) ) );
      }
    }
    $variacao = json_encode( $variacao, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

    $visible = mysqli_real_escape_string( $db_con, $_POST['visible'] );
    $integrado = mysqli_real_escape_string( $db_con, $_POST['integrado'] );
    $status = "1";

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      if( $_FILES['destaque']['name'] ) {

        $upload = upload_image( $eid, $_FILES['destaque'] );
        

        
        if ( $upload['status'] == "1" ) {
          $destaque = $upload['url'];
        } else {
          $checkerrors++;
          for( $x=0; $x < count( $upload['errors'] ); $x++ ) {
            $errormessage[] = $upload['errors'][$x];
          }
        }

      }

      // -- Estabelecimento

      if( !$eid ) {
        $checkerrors++;
        $errormessage[] = "O estabelecimento não pode ser nulo";
      }

      // -- Nome

      if( !$nome ) {
        $checkerrors++;
        $errormessage[] = "O nome não pode ser nulo";
      }
      
      // -- Nome

      if( !$estoque ) {
        $checkerrors++;
        $errormessage[] = "Este produto controla estoque?";
      }
      
      // -- Categorias

      if( !$categoria ) {
        $checkerrors++;
        $errormessage[] = "Cadastre e selecione uma categoria";
      }

      // -- Destaque

      if( !$destaque ) {
        $checkerrors++;
        $errormessage[] = "A imagem destaque não pode ser nula";
      }

      // -- Valor

      //if( !$valor ) {
      //  $checkerrors++;
      //  $errormessage[] = "O valor não pode ser nulo";
      //}

    // Executar registro

    if( !$checkerrors ) {

        if( $pid = new_produto( $eid,$categoria,$destaque,$estoque,$posicao,$ref,$nome,$video_link,$descricao,$valor,$oferta,$valor_promocional,$variacao,$status,$visible,$integrado,$medidas ) ) {

        if ( $_FILES['file'] ) {

            for ($i = 0; $i < count( $_FILES['file']['name'] ); $i++) {

                $file_name = $_FILES['file']['name'][$i];
                $file_size = $_FILES['file']['size'][$i];
                $file_tmp = $_FILES['file']['tmp_name'][$i];
                $file_type = $_FILES['file']['type'][$i];
                $upload = upload_image_direct( $estabelecimento,$file_name,$file_size,$file_tmp,$file_type );

                if ( $upload['status'] == "1" ) {
                
                  $url = $upload['url'];

                  mysqli_query( $db_con, "INSERT INTO midia (type,rel_estabelecimentos_id,rel_id,url) VALUES ('1','$estabelecimento','$pid','$url')");

                }    
                         
            }

        }

        header("Location: ../index.php?msg=sucesso");

      } else {

        header("Location: ../index.php?msg=erro");

      }

    }

  }
  
?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

        <div class="title-icon pull-left">
          <i class="lni lni-shopping-basket"></i>
          <span>Adicionar Produtos</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/produtos">Produtos</a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/produtos/adicionar">Adicionar</a>
          </div>
        </div>
        
			</div>

		</div>

		<!-- Content -->

		<div class="data box-white mt-16">

      <form id="the_form" class="form-default" method="POST" enctype="multipart/form-data">

          <div class="row">

            <div class="col-md-12">

              <?php if( $checkerrors ) { list_errors(); } ?>

              <?php if( $_GET['msg'] == "erro" ) { ?>

                <?php modal_alerta("Erro, tente novamente!","erro"); ?>

              <?php } ?>

              <?php if( $_GET['msg'] == "sucesso" ) { ?>

                <?php modal_alerta("Cadastro efetuado com sucesso!","sucesso"); ?>

              <?php } ?>

            </div>

          </div>

          <div class="row">

            <div class="col-md-4">
              <div class="form-field-default">
               <label>Categoria:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select id="input-categoria" name="categoria">
                      <option value=""></option>
                      <?php 
                      $quicksql = mysqli_query( $db_con, "SELECT * FROM categorias WHERE rel_estabelecimentos_id = '$eid' ORDER BY nome ASC LIMIT 999" );
                      while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
                      ?>
                      <option <?php if( $_POST['categoria'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>
                      <?php } ?>
                    </select>
                    <div class="clear"></div>
                  </div>
              </div>
            </div>
			
			<div class="col-md-4">
              <div class="form-field-default">
               <label>Controla Estoque:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select id="input-categoria" name="estoque">
                      <option value="1">Não</option>
					  <option value="2">Sim</option>
                    </select>
                    <div class="clear"></div>
                  </div>
              </div>
            </div>
			
			<div class="col-md-4">
              <div class="form-field-default">
               <label>Quantidade Estoque:</label>
                    <input type="text" name="posicao" value="0">
              </div>
            </div>

          </div>

          <div class="row">

            <div class="col-md-12">
              <label>Foto destaque:</label>
              <div class="file-preview">

                <div class="image-preview image-preview-product" id="image-preview" style='background: url("") no-repeat center center; background-size: auto 102%;'>
                  <label for="image-upload" id="image-label">Clique ou arraste</label>
                  <input type="file" name="destaque" id="image-upload"/>
                </div>
                <span class="explain">Selecione a foto destaque clicando no campo ou arrastando o arquivo!</span>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                <label>Galeria:</label>
                <div class="field-gallery">
                  <div class="input-gallery" style="padding-top: .5rem;">
                  </div>
                </div>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>REF:</label>
                  <span class="form-tip">Código para identificar o seu produto no seu estoque, caso deixe em branco, será definido automaticamente.</span>
                  <input type="text" name="ref" placeholder="REF" value="<?php echo htmlclean( $_POST['ref'] ); ?>">

              </div>

            </div>

          </div>
          
          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>ID do vídeo destaque:</label>
                  <input type="text" name="video_link" placeholder="ID do vídeo" value="<?php echo htmlclean( $_POST['video_link'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Nome:</label>
                  <input type="text" id="input-nome" name="nome" placeholder="Nome" value="<?php echo htmlclean( $_POST['nome'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Descrição:</label>
                  <textarea rows="6" name="descricao" placeholder="Descrição do seu produto"><?php echo htmlclean( $_POST['descricao'] ); ?></textarea>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Valor:</label>
                  <input class="maskmoney" type="text" name="valor" placeholder="Valor">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Este produto está em oferta?</label>
                  <div class="form-field-radio">
                    <input type="radio" name="oferta" value="1" element-show=".elemento-promocional" <?php if( $_POST['oferta'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                  </div>
                  <div class="form-field-radio">
                    <input type="radio" name="oferta" value="2" element-hide=".elemento-promocional" <?php if( $_POST['oferta'] == 2 OR !$_POST['oferta']  ){ echo 'CHECKED'; }; ?>> Não
                  </div>
                  <div class="clear"></div>

              </div>

            </div>

          </div>

          <div class="row elemento-promocional elemento-oculto">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Valor promocional:</label>
                  <input class="maskmoney" type="text" name="valor_promocional" placeholder="Valor promocional" value="<?php echo htmlclean( $_POST['valor_promocional'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="panel-group panel-filters panel-variacoes">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#collapse-variacao">
                        <span class="desc">Variações</span>
                        <i class="lni lni-funnel"></i>
                        <div class="clear"></div>
                      </a>
                    </h4>
                  </div>
                  <div id="collapse-variacao" class="panel-collapse collapse <?php if( $_SESSION['estabelecimento']['funcionalidade_variacao'] == "1" ) { echo "in"; } ?>">
                    <div class="panel-body">

                      <?php if( $_SESSION['estabelecimento']['funcionalidade_variacao'] == "1" ) { ?>
                      
                        <!-- Variações -->

                        <div class="variacoes">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="render-variacoes"></div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="adicionar adicionar-variacao">
                                <i class="lni lni-plus"></i>
                                <span>Adicionar variação</span>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- / Variações -->

                      <?php } else { ?>

                        <div class="expiration-info variacao-hire">
                          <div class="row">
                            <div class="col-md-9">
                              <span class="msg">O seu plano não possuí suporte para variações de produto, visite a nossa seção de planos e adquira um com a funcionalidade.</span>
                            </div>
                            <div class="col-md-3">
                              <div class="add-new add-center text-center">
                                <a href="<?php panel_url(); ?>/plano/listar">
                                  <span>Ver planos</span>
                                  <i class="lni lni-plus"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>

                      <?php } ?>

                    </div>
                  </div>
                </div>
              </div> 

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Produto visível?</label>
                  <span class="form-tip">Se habilitado o produto irá aparecer em todo site, caso contrário, irá aparecer apenas para quem você compartilhar o link.</span>
                  <div class="radios">
                    <div class="spacer"></div>
                    <div class="form-field-radio">
                      <input type="radio" name="visible" value="1" <?php if( $_POST['visible'] == 1 OR !$_POST['visible'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="visible" value="2" <?php if( $_POST['visible'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                  </div>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Produto integrado?</label>
                  <span class="form-tip">Se habilitado o produto irá aparecer nas sacolinhas do instagram / facebook.</span>
                  <div class="radios">
                    <div class="spacer"></div>
                    <div class="form-field-radio">
                      <input type="radio" name="integrado" value="1" <?php if( $_POST['integrado'] == 1 OR !$_POST['integrado'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="integrado" value="2" <?php if( $_POST['integrado'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                  </div>

              </div>

            </div>

          </div>
          
          
          <div class="row">
            <div class="col-md-12">
              <label>Medidas</label>
              <span class="form-tip">Informe as medidas a seguir para calculo do frete.</span>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-3">
                <div class="form-field-default">
                <label>Altura:(cm)</label>
                      <input class="maskmedida" type="text" name="altura" value="" placeholder="Ex: 2">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-field-default">
                <label>Largura:(cm)</label>
                      <input class="maskmedida" type="text" name="largura" value="" placeholder="Ex: 12">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-field-default">
                <label>Comprimento:(cm)</label>
                      <input class="maskmedida" type="text" name="comprimento" value="" placeholder="Ex: 17">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-field-default">
                <label>Peso:(kg)</label>
                      <input class="maskmedida" type="text" name="peso" value="" placeholder="Ex: 0,150">
                </div>
            </div>
          </div>

          <div class="row lowpadd">

          	<div class="col-md-6 col-sm-5 col-xs-5">
          		<div class="form-field form-field-submit">
  							<a href="javascript:history.back(1)" class="backbutton pull-left">
  								<span><i class="lni lni-chevron-left"></i> Voltar</span>
  							</a>
  						</div>
          	</div>

  					<div class="col-md-6 col-sm-7 col-xs-7">
  						<input type="hidden" name="formdata" value="true"/>
  						<div class="form-field form-field-submit">
  							<button class="pull-right">
  								<span>Cadastrar <i class="lni lni-chevron-right"></i></span>
  							</button>
  						</div>
  					</div>

          </div>

      </form>

		</div>

		<!-- / Content -->

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include('../../_layout/rdp.php');
include('../../_layout/footer.php');
?>

<script>

$(document).ready( function() {
          
  // Globais

  $("#the_form").validate({

      /* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */

      rules:{

        estabelecimento_id:{
        required: true
        },
        nome:{
        required: true
        },
        destaque:{
        required: true
        },
        categoria:{
        required: true
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        estabelecimento_id:{
          required: "Esse campo é obrigatório"
        },
        nome:{
          required: "Esse campo é obrigatório"
        },
        categoria:{
          required: "Cadastre e selecione uma categoria"
        },
        destaque:{
          required: "Obrigatório"
        }
         

      }

    });

  });

</script>

<script>

$( document ).ready(function() {

  $( "input[name=estabelecimento_id]" ).change(function() {
      var estabelecimento = $(this).val();
      $("#input-categoria").html("<option>-- Carregando categorias --</option>");
      $("#input-categoria").load("<?php just_url(); ?>/_core/_ajax/categorias.php?estabelecimento="+estabelecimento);
  });

});

</script>

<script>
$(document).ready(function() {
    
  // Preview avatar
  $.uploadPreview({
    input_field: "#image-upload",
    preview_box: "#image-preview",
    label_field: "#image-label",
    label_default: "Envie ou arraste",
    label_selected: "Trocar imagem",
    no_label: false
  });

  // Preview capa
  $.uploadPreview({
    input_field: "#image-upload2",
    preview_box: "#image-preview2",
    label_field: "#image-label2",
    label_default: "Envie ou arraste",
    label_selected: "Trocar imagem",
    no_label: false
  });

});

</script>
  
<script>

  function masks() {

    $(".maskdate").mask("99/99/9999",{placeholder:""});
    $(".maskrg").mask("99999999-99",{placeholder:""});
    $(".maskcpf").mask("999.999.999-99",{placeholder:""});
    $(".maskcnpj").mask("99.999.999/9999-99",{placeholder:""});
    $(".maskcel").mask("(99) 99999-9999");
    $(".maskcep").mask("99999-999");
    $(".dater").mask("99/99/9999");
    $(".masktime").mask("99:99:99");
    $(".masknumber").mask("99");
    $(".maskmoney").maskMoney({
        prefix: "R$ ",
        decimal: ",",
        thousands: "."
    });

  }

  function reordena() {

    var variacao = 0;

    $( ".variacao" ).each(function() {
      
      $( this ).closest(".panel-subvariacao").find(".subvariacao-link").attr("href","#collapse-subvariacao-"+variacao);
      $( this ).closest(".panel-subvariacao").find(".subvariacao-body").attr("id","collapse-subvariacao-"+variacao);
      $( this ).closest(".panel-subvariacao").find(".adicionar-item").attr("variacao-id",variacao);
      $( this ).attr("variacao-id",variacao);
      $( this ).find(".col-item").attr("variacao-id",variacao);
      $( this ).find(".variacao-nome").attr("name","variacao["+variacao+"][nome]");
      $( this ).find(".variacao-escolha-minima").attr("name","variacao["+variacao+"][escolha_minima]");
      $( this ).find(".variacao-escolha-maxima").attr("name","variacao["+variacao+"][escolha_maxima]");
      variacao++;
    
      var item = 0;

      $( this ).find(".item").each(function() {

        var variacao_pai = $( this ).parent().attr('variacao-id');
        $( this ).attr("item-id",item);
        $( this ).find(".item-nome").attr("name","variacao["+variacao_pai+"][item]["+item+"][nome]");
        $( this ).find(".item-descricao").attr("name","variacao["+variacao_pai+"][item]["+item+"][descricao]");
        $( this ).find(".item-valor").attr("name","variacao["+variacao_pai+"][item]["+item+"][valor]");
        item++;

      });

    });

  }

  <?php if( $_SESSION['estabelecimento']['funcionalidade_variacao'] == "1" ) { ?>

    $(document).on('click', '.adicionar-variacao', function() {

        var render;
        $.get("<?php echo get_just_url(); ?>/_core/_ajax/variacoes.php?modo=variacao", function(data){
            render = data;
            $(".render-variacoes").append(render);
            $(" .panel-subvariacao ").fadeIn(400);
            reordena();
            masks();
        });

    });

    $(document).on('click', '.adicionar-item', function() {

        var render;
        var parent = $( this ).closest(".variacao").attr("variacao-id");
        $.get("<?php echo get_just_url(); ?>/_core/_ajax/variacoes.php?modo=item", function(data){
            render = data;
            $('.variacao[variacao-id="'+parent+'"] .render-itens').find('.col-md-4:last-child').before(render);
            $(" .col-item ").fadeIn(400);
            reordena();
            masks();
        });

    });

    $(document).on('keyup', '.variacao-nome', function() {

      $( this ).closest(".panel-subvariacao").find(".variacao-desc").html( $( this ).val() );

    });

    $(document).on('click', '.deletar-variacao', function() {

      if( confirm("Tem certeza que deseja remover essa variação?") ) {
        $( this ).closest(".panel-subvariacao").remove();
        reordena();
      }

    });

    $(document).on('click', '.deletar-item', function() {

      if( confirm("Tem certeza que deseja remover esse item?") ) {
        $( this ).closest(".col-item").remove();
        reordena();
      }

    });

    $(document).on('keyup keydown', '.variacao-escolha-minima', function(o) {
      var re = /[^0-9\-]/;
      var strreplacer = $(this).val();
      strreplacer = strreplacer.replace(re, '');
      if( strreplacer == '' || strreplacer == '0' ) {
        strreplacer = '0';
      }
      $(this).val( strreplacer );
    });

    $(document).on('keyup keydown', '.variacao-escolha-maxima', function(o) {
      var re = /[^0-9\-]/;
      var strreplacer = $(this).val();
      strreplacer = strreplacer.replace(re, '');
      if( strreplacer == '' || strreplacer == '0' ) {
        strreplacer = '1';
      }
      $(this).val( strreplacer );
    });

  <?php } ?>

</script>

<script>

var themaxfiles = <?php echo $gallery_max_files; ?>;

function watchadd() {

  $('.add-gallery-button').remove();
  var thetotal = $('.uploaded-image').size();
  if( thetotal < themaxfiles ) {
    $('.field-gallery .uploaded').append("<div class='add-gallery-button' title='Adicionar imagem'></div>");
  }

}

function kill_image(fileid) {
    // alert(fileid);
    watchadd();
}

</script>

<script>
    $(document).ready(function() {

        let preloaded = [];

        $('.input-gallery').imageUploader({
            preloaded: preloaded,
            imagesInputName: 'file',
            preloadedInputName: 'old',
            maxFiles: themaxfiles
        });

        $( ".addnew" ).click(function() {

            $( ".input-gallery input[type=file]" ).trigger("click");

        });

        watchadd();

    });
</script>