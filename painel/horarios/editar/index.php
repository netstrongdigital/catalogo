<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
restrict_expirado();
// SEO
$seo_subtitle = "Editar agendamento";
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
  $eid = $_SESSION['estabelecimento']['id'];

  $id = mysqli_real_escape_string( $db_con, $_GET['id'] );
  $edit = mysqli_query( $db_con, "SELECT * FROM agendamentos WHERE id = '$id' AND rel_estabelecimentos_id = '$eid' LIMIT 1");
  $hasdata = mysqli_num_rows( $edit );
  $data = mysqli_fetch_array( $edit );

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

    $estabelecimento = $_SESSION['estabelecimento']['id'];
    $sun = mysqli_real_escape_string( $db_con, $_POST['sun'] );
    if( !$sun ) {
      $sun = "0";
    }
    $mon = mysqli_real_escape_string( $db_con, $_POST['mon'] );
    if( !$mon ) {
      $mon = "0";
    }
    $tue = mysqli_real_escape_string( $db_con, $_POST['tue'] );
    if( !$tue ) {
      $tue = "0";
    }
    $wed = mysqli_real_escape_string( $db_con, $_POST['wed'] );
    if( !$wed ) {
      $wed = "0";
    }
    $thu = mysqli_real_escape_string( $db_con, $_POST['thu'] );
    if( !$thu ) {
      $thu = "0";
    }
    $fri = mysqli_real_escape_string( $db_con, $_POST['fri'] );
    if( !$fri ) {
      $fri = "0";
    }
    $sat = mysqli_real_escape_string( $db_con, $_POST['sat'] );
    if( !$sat ) {
      $sat = "0";
    }
    $hora = mysqli_real_escape_string( $db_con, $_POST['hora'] );
    $acao = mysqli_real_escape_string( $db_con, $_POST['acao'] );

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // -- Estabelecimento

      if( !$estabelecimento ) {
        $checkerrors++;
        $errormessage[] = "O estabelecimento não pode ser nulo";
      }

      // -- Ação

      if( !$acao ) {
        $checkerrors++;
        $errormessage[] = "A ação não pode ser nula";
      }

      // -- Hora

      if( !$hora ) {
        $checkerrors++;
        $errormessage[] = "A hora não pode ser nula";
      }

    // Executar registro

    if( !$checkerrors ) {

      if( edit_agendamento( $id,$sun,$mon,$tue,$wed,$thu,$fri,$sat,$hora,$acao ) ) {

        header("Location: index.php?msg=sucesso&id=".$id);

      } else {

        header("Location: index.php?msg=erro&id=".$id);

      }

    }

  }
  
?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

        <div class="title-icon pull-left">
          <i class="lni lni-ticket"></i>
          <span>Editar Horário de Funcionamento</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/horarios">Horário de Funcionamento</a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/horarios/editar?id=<?php echo $id; ?>">Editar</a>
          </div>
        </div>

			</div>

		</div>

		<!-- Content -->

		<div class="data box-white mt-16">

      <?php if( $hasdata ) { ?>

      <form id="the_form" class="form-default" method="POST" enctype="multipart/form-data">

          <div class="row">

            <div class="col-md-12">

              <?php if( $checkerrors ) { list_errors(); } ?>

              <?php if( $_GET['msg'] == "erro" ) { ?>

                <?php modal_alerta("Erro, tente novamente!","erro"); ?>

              <?php } ?>

              <?php if( $_GET['msg'] == "sucesso" ) { ?>

                <?php modal_alerta("Alterado com sucesso!","sucesso"); ?>

              <?php } ?>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6">

              <div class="form-field-default form-field-radio-new">

                  <input type="checkbox" name="mon" value="1" <?php if( $data['mon'] == "1" ){ echo 'CHECKED="CHECKED"'; }; ?>>
                  <label>Segunda-feira</label>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default form-field-radio-new">

                  <input type="checkbox" name="tue" value="1" <?php if( $data['tue'] == "1" ){ echo 'CHECKED="CHECKED"'; }; ?>>
                  <label>Terça-feira</label>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default form-field-radio-new">

                  <input type="checkbox" name="wed" value="1" <?php if( $data['wed'] == "1" ){ echo 'CHECKED="CHECKED"'; }; ?>>
                  <label>Quarta-feira</label>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default form-field-radio-new">

                  <input type="checkbox" name="thu" value="1" <?php if( $data['thu'] == "1" ){ echo 'CHECKED="CHECKED"'; }; ?>>
                  <label>Quinta-feira</label>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default form-field-radio-new">

                  <input type="checkbox" name="fri" value="1" <?php if( $data['fri'] == "1" ){ echo 'CHECKED="CHECKED"'; }; ?>>
                  <label>Sexta-feira</label>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default form-field-radio-new">

                  <input type="checkbox" name="sat" value="1" <?php if( $data['sat'] == "1" ){ echo 'CHECKED="CHECKED"'; }; ?>>
                  <label>Sábado</label>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default form-field-radio-new">

                  <input type="checkbox" name="sun" value="1" <?php if( $data['sun'] == "1" ){ echo 'CHECKED="CHECKED"'; }; ?>>
                  <label>Domingo</label>

              </div>

            </div>

          </div>
          
          <hr/>
          <p>ATENÇÃO: Verifique abaixo o horário atual do servidor e ajuste a abertura e fechamento conforme sua necessidade.</p>
          <hr/>

          <div class="row">
              
            <div class="col-md-4">

              <div class="form-field-default">

                  <label>Hora do Servidor:</label>
                  <input class="masktimemin" type="text" value="<?php echo date("H:i"); ?>">

              </div>

            </div>  


            <div class="col-md-4">

              <div class="form-field-default">

                  <label>Horário:</label>
                  <input class="masktimemin" type="text" name="hora" placeholder="00:00" value="<?php echo htmlclean( $data['hora'] ); ?>">

              </div>

            </div>
            
            <div class="col-md-4">

              <div class="form-field-default">

                  <label>Ação:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select name="acao">
                      <option></option>
                      <?php for( $x = 0; $x < count( $numeric_data['agendamento_acao'] ); $x++ ) { ?>
                      <option value="<?php echo $numeric_data['agendamento_acao'][$x]['value']; ?>" <?php if( $data['acao'] == $numeric_data['agendamento_acao'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['agendamento_acao'][$x]['name']; ?></option>
                      <?php } ?>
                    </select>
                    <div class="clear"></div>
                  </div>

              </div>

            </div>
            
            
            

          </div>

           

          <div class="row lowpadd">

            <div class="col-md-6 col-sm-5 col-xs-5">
              <div class="form-field form-field-submit">
                <a href="<?php panel_url(); ?>/horarios" class="backbutton pull-left">
                  <span><i class="lni lni-chevron-left"></i> Voltar</span>
                </a>
              </div>
            </div>

            <div class="col-md-6 col-sm-7 col-xs-7">
              <input type="hidden" name="formdata" value="true"/>
              <div class="form-field form-field-submit">
                <button class="pull-right">
                  <span>Salvar <i class="lni lni-chevron-right"></i></span>
                </button>
              </div>
            </div>

          </div>

      </form>

      <?php } else { ?>

        <span class="nulled nulled-edit color-red">Erro, inválido ou não encontrado!</span>

      <?php } ?>

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

        estabelecimento:{
        required: true
        },
        hora:{
        required: true
        },
        acao:{
        required: true
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        estabelecimento:{
          required: "Esse campo é obrigatório"
        },
        hora:{
          required: "Esse campo é obrigatório"
        },
        acao:{
          required: "Esse campo é obrigatório"
        }

      }

    });

  });

</script>