$(document).ready(function() {





    $("input[name=endereco_cep]").keyup(function() {





        var cep = $(this).val().replace(/\D/g, '');





        if (cep != "") {





            var validacep = /^[0-9]{8}$/;





            if(validacep.test(cep)) {





                $("input[name=endereco_rua]").val("...");


                $("input[name=endereco_bairro]").val("...");


                $("input[name=endereco_cidade]").val("...");


                $("input[name=endereco_estado]").val("...");




                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {





                    preencherEndereco(dados);


                });


            }


            else {


            }





        }


        else {


        }


        


    });





});

function preencherEndereco(dados) {
    if (!("erro" in dados)) {
        $("input[name='endereco_rua']").val(dados.logradouro || "");
        $("input[name='endereco_bairro']").val(dados.bairro || "");
        $("input[name='endereco_cidade']").val(dados.localidade || "");
        $("input[name='endereco_estado']").val(dados.uf || "");
    } else {
        $("input[name='endereco_rua']").val("");
        $("input[name='endereco_bairro']").val("");
        $("input[name='endereco_cidade']").val("");
        $("input[name='endereco_estado']").val("");
    }
}