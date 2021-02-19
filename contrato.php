<?php

include("conexao.php");
include('pdf/mpdf.php');

// Dados de login //

$id = $_SESSION['idUser'];
$funcao = $_POST['funcao'];

$consulta = $pdo->query("SELECT * FROM users where id = $id;");

while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $name = $linha['nome'];
    $tipo_usuario = $linha['tipo_user'];
    $codigo_corretor = $linha['codigo_corretor'];
    $user_cpf = $linha['cpf'];
    $user_tel = $linha['telefone'];
}


// fim dos dados de login //

$conexao = mysqli_connect("grupocontem.com.br", "grupocon_conexao", "c0Nt3m#2@1p", "grupocon_vendapj") or die("Sem conexao");
mysqli_set_charset($conexao, "utf8");

if (isset($_GET['proposta'])){
    $proposta = $_GET['proposta'];
} else {
    $proposta = null;
}

// Dados da proposta //

$sql = mysqli_query($conexao, "SELECT * FROM wp_contratospj where id = '$proposta'");
$dados = mysqli_fetch_array($sql);
$codigo_corretor_proposta = $dados['codigo_corretor'];

$consulta2 = $pdo->query("SELECT * FROM users where codigo_corretor = $codigo_corretor_proposta;");
  while ($linha2 = $consulta2->fetch(PDO::FETCH_ASSOC)) {
      $nome_corretor_cadastrado = $linha2['nome'];
      $cpf_corretor_cadastrado = $linha2['cpf'];
      $telefone_corretor_cadastrado = $linha2['telefone'];
      $distribuidor_corretor_cadastrado = $linha2['distribuidora'];
      $tipousuario_cadastrado = $linha2['tipo_user'];
      $codigo_corretor_corretor_cadastrado = $linha2['codigo_corretor'];
  }

if($codigo_corretor_proposta == $codigo_corretor || $tipo_usuario == "ADMIN"){
    
} else {
  header('Location: https://painel.grupocontem.com.br/contratospj.php');
}

$count_benefic_query = "SELECT * FROM wp_beneficiario where proposta = '$proposta'";
$exec = mysqli_query($conexao, $count_benefic_query);
$dados_benefic = mysqli_fetch_array($exec);
$qtd_benefic = mysqli_num_rows($exec);

$count_dep_query = "SELECT * FROM wp_dependente where proposta = '$proposta'";
$exec_dep = mysqli_query($conexao, $count_dep_query);
$qtd_dep = mysqli_num_rows($exec_dep);

$operadora_escolhida = $dados['operadora'];
$sql_operadora = mysqli_query($conexao, "SELECT * FROM wp_operadora where nome_operadora = '$operadora_escolhida'");
$dados_operadora = mysqli_fetch_array($sql_operadora);

$produto_escolhido = $dados['produto'];
$sql_plano = mysqli_query($conexao, "SELECT p.nome_plano, p.registro_ans, p.acomodacao, p.segmentacao, p.abrangencia, p.operadora
                                        FROM `wp_beneficiario` as b
                                        Inner join wp_produtos as p
                                        where b.proposta = '$proposta'
                                        and b.produto = p.id
                                        group by b.produto;");

while($listar_planos = mysqli_fetch_array($sql_plano)) {
    $planos[] = '<tr><td><div style="font-size: 11px;"> '.$listar_planos['nome_plano'].' </div></td>
               <td><div style="font-size: 11px;"> '.$listar_planos['registro_ans'].' </div></td>
               <td><div style="font-size: 11px;"> '.$listar_planos['acomodacao'].' </div></td>
               <td><div style="font-size: 11px;">  '.$listar_planos['segmentacao'].' </div></td>
               <td><div style="font-size: 11px;"> '.$listar_planos['abrangencia'].' </div></td></tr>';
  }

$vigencia_escolhida = $dados['vigencia'];

$sql_vigencias = mysqli_query($conexao, "SELECT * FROM wp_vigencias where id = '$vigencia_escolhida' order by vigencia");
$vigencia_exec = mysqli_fetch_array($sql_vigencias);

$qtd_vigencias = count($vigencias_checkbox);


//Variaves editadas
$data = date('d/m/Y', strtotime($dados['data_contrato']));

//Desenho do contrato

if($dados['operadora'] == 'CEMERU') {
    $header_empresa = '<img src="https://www.grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/cabecalho/CEMERU_EMPRESA.jpg">';
    $header_benefic = '<img src="https://www.grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/cabecalho/CEMERU_BENEFIC.jpg">';
} else if($dados['operadora'] == 'LIFEDAY'){
    $header_empresa = '<img src="https://www.grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/cabecalho/LIFEDAY_EMPRESA.jpg">';
    $header_benefic = '<img src="https://www.grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/cabecalho/LIFEDAY_BENEFIC.jpg">';
} else if($dados['operadora'] == 'VERTE'){
    $header_empresa = '<img src="https://www.grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/cabecalho/VERTE_EMPRESA.jpg">';
    $header_benefic = '<img src="https://www.grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/cabecalho/VERTE_BENEFIC.jpg">';
}

$info_plano .= '<style> td, th{padding:3px}</style>';
    $info_plano .= '<br><div style="margin-top: 50px; padding: 5px; background-color: #d3d3d3">'.
                  'Data de início de vigência, cobertura e vencimento do 1º boleto bancário'.
                  '<font color="#d3d3d3">&&&&&</font> ____/____/________'./*$data*/'</div>'.
                  '<div style="padding: 5px; background-color: #808080; font-size: 13px" >'.
                  'Porte da Empresa / Vigência / Opção de Plano <font color="#808080">&&&&&&&&&&&&&&&&&&&&&&&&</font> Proposta Nº: ' .$dados['id'].'</font></div>'.


                  '<table style="width: 100%;" class="borda">'.
                    '<tr>
                      <td>

                        <b>Porte da empresa: 02 à '.$dados_operadora['porte_maximo'].' Beneficiários</b>'.
                        '<div style="font-size: 11px; margin-top: 8px;">A empresa terá o mês agosto como o mês base de reajuste anual, <br>'.
                        'independente do mês de contratação / início do contrato.

                       </td>

                      <td>

                        <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; text-align: center; margin-top: 20px;" class="borda">

                          <tr>
                            <th> <div style="font-size: 10px;"> Ultima data para entrega <br>da documentação na <br>administradora </div></th>
                            <th> <div style="font-size: 10px;"> Data da vigilância e do vencimento <br>do primeiro <br>boleto bancário </div></th>
                          </tr>

                          <tr>
                            <td><div style="font-size: 11px;">Até o dia '.$vigencia_exec['fechamento'].' </div></td>
                            <td><div style="font-size: 11px;"> '.$vigencia_exec['vigencia'].' '.$vigencia_exec['quando'].' </div></td>
                          </tr>

                          </table>

                      </td>
                    </tr>
                   </table>

                   <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; text-align: center; margin-top: 5px;">

                     <tr>
                       <th> Data de assinatura da proposta </div></th>
                       <th> Vigência e pagamento de boletos </div></th>
                     </tr>

                     <tr>
                       <td> ______/______/__________ </td>
                       <td> Vencimento <b>'.$vigencia_exec['vigencia'].'</b> </td>
                    </tr>
                 </table>

                 <table border="1" cellspacing="0" style="width: 100%; text-align: center; margin-top: 20px; margin-top: 15px;">

                   <tr>
                     <th><div style="font-size: 11px;"> Planos</div></th>
                     <th><div style="font-size: 11px;"> Registro ANS </div></th>
                     <th><div style="font-size: 11px;"> Acomodação </div></th>
                     <th><div style="font-size: 11px;"> Segmentação Assistencial </div></th>
                     <th><div style="font-size: 11px;"> Abrangência Geográfica </div></th>
                   </tr>';

                   for ($i=0; $i<=count($planos); $i++){
                     $info_plano .= $planos[$i];
                   }

                   $info_plano .= '</table>';

            $dados_empresa .= '<style> td, th{padding:5px}</style>';
            $dados_empresa .= '<br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px;">
            <b>Dados da Empresa Contratante </b></div>
            <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 0px;">

              <tr>
               <td> <b>CNPJ: </b>'.$dados['cnpj'].' </td>
               <td> <b>Razão Social: </b>'.$dados['razao_social'].'</td>
              </tr>

              <tr>
               <td colspan="2"> <b>Nome Fantasia: </b>'.$dados['nome_fantasia'].'</td>
              </tr>

              <tr>
                <td> <b>Insc. Municipal: </b>'.$dados['insc_municipal'].' </div></td>
                <td> <b>Insc. Estadual: </b>'.$dados['insc_estadual'].' </div></td>
              </tr>

            </table>


            <br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px;">
            <b>Endereço (CNPJ) </b></div>
            <table border="1" cellspacing="-1" style="width: 100%; font-size: 12px; margin-top: 0px;">
              <tr>
               <td colspan="3"> <b>Logradouro: </b>'.$dados['logradouro_empresa'].' </td>
              </tr>

              <tr>
               <td> <b>Número: </b>'.$dados['numero_empresa'].'</td>
               <td> <b>Bairro: </b>'.$dados['bairro_empresa'].'</td>
               <td> <b>Complemento: </b>'.$dados['complemento_empresa'].'</td>
              </tr>
              <tr>
                <td> <b>UF: </b>'.$dados['uf_empresa'].'</td>
                <td> <b>Município: </b>'.$dados['cidade_empresa'].'</td>
                <td> <b>CEP: </b>'.$dados['cep_empresa'].'</td>
              </tr>
              <tr>
                <td> <b>Tel. (Empresa): </b>'.$dados['telefone_empresa'].'</td>
                <td> <b>Celular (Empresa): </b>'.$dados['telefone_celular'].'</td>
                <td> <b>Telefone (Cobrança): </b>'.$dados['telefone_cobranca'].'</td>
              </tr>
            </table>


            <table border="1" cellspacing="-1" style="width: 100%; font-size: 12px; margin-top: 0px;">
              <tr>
               <td colspan="3"> <b>Logradouro (Cobrança): </b>'.$dados['logradouro_empresa'].' </td>
              </tr>

              <tr>
               <td> <b>Número (Cobrança): </b>'.$dados['numero_empresa'].'</td>
               <td> <b>Bairro (Cobrança): </b>'.$dados['bairro_empresa'].'</td>
               <td> <b>Complemento (Cobrança): </b>'.$dados['complemento_empresa'].'</td>
              </tr>
              <tr>
                <td> <b>UF (Cobrança): </b>'.$dados['uf_empresa'].'</td>
                <td> <b>Município (Cobrança): </b>'.$dados['cidade_empresa'].'</td>
                <td> <b>CEP (Cobrança): </b>'.$dados['cep_empresa'].'</td>
              </tr>

            </table>

            <br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px;">
            <b>Sócio / Representante legal assinante do contrato </b></div>
            <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 0px;">

              <tr>
               <td colspan="2"> <b>Nome: </b>'.$dados['nome_socio'].' </td>
               <td> <b>CPF: </b>'.$dados['cpf_socio'].'</td>
              </tr>

              <tr>
               <td> <b>Telefone: </b>'.$dados['telefone_socio'].'</td>
               <td> <b>Email: </b>'.$dados['email_socio'].' </td>
               <td> <b>Cargo: </b>'.$dados['cargo_socio'].'</td>
              </tr>
            </table>

            <table cellspacing="-1" style="width: 100%; font-size: 12px; margin-top: 40px;">
              <tr>
               <td><center> __________________________________________________ </center></td>
               <td><center> __________________________________________________ </center></td>
              </tr>

              <tr>
               <td> <center>Local e data</center> </td>
               <td> <center>Assinatura do representante legal da empresa</center> </td>
              </tr>
            </table>';

            $footer .= '<div style="padding: 5px; background-color: black; font-size: 9px; color: white">
            Versão junho / 2020 - documento em três vias de igual teor. Todos os dados são de preenchimento OBRIGATÓRIO.
            <br>1° via - CONTÉM | 2° via - OPERADORA |<font color="black">&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&</font>
            <font size="100px"> {PAGENO}';

            $carta .= '<style> td, th{padding:3px}</style>';
    $carta .= '<br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top:50px;">
              <font color="#d3d3d3">&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&</font><b> Proposta Nº: ' .$dados['id'].'</b></div>
              <br><div style="margin-top: 5px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; font-size: 17px; text-align: center; padding: 5px; background-color: #d3d3d3">
              <b>Carta de Orientação ao Beneficiário</b></div>

              <div style="font-size: 12px; text-align: justify; margin-top: 10px;">
              Prezado(a) Beneficiário(a), A Agência Nacional de Saúde (ANS), instituição que regula as atividades das operadoras de planos
              privados de assistência à saúde, e tem como missão defender o interesse público vem, por meio desta, prestar informações para o
              preenchimento da DECLARAÇÃO DE SAÚDE.
              <br><br>
              1. O QUE É A DECLARAÇÃO DE SAÚDE? É o formulário que acompanha o Contrato do Plano de Saúde, onde o beneficiário ou
              representante legal deverá informar as doenças ou lesões preexistentes que saiba ser portador ou sofredor no momento da
              contratação do plano. Para o seu preenchimento, o beneficiário tem o direito de ser orientado, gratuitamente, por um médico
              credenciado/referenciado pela operadora. Portanto, se o beneficiário (você) toma medicamentos regularmente, consulte seu médico
              por problema de saúde do qual conhece o diagnóstico, fez qualquer exame que identificou alguma doença ou lesão, esteve internado
              ou submeteu-se a alguma cirurgia, DEVE DECLARAR ESTA DOENÇA OU LESÃO.
              <br><br>
              2. AO DECLARAR AS DOENÇAS E/OU LESÕES QUE O BENEFICIÁRIO SAIBA SER PORTADOR NO MOMENTO DA CONTRATAÇÃO: •
              A operadora NÃO poderá impedi-lo de contratar o plano de saúde. Caso isto ocorra, encaminhe a denúncia a ANS. • A operadora,
              deverá oferecer: cobertura total ou COBERTURA PARCIAL TEMPORÁRIA (CPT), podendo ainda oferecer o Agravo, que é um
              acréscimo no valor da mensalidade, pago ao plano privado de assistência à saúde, para que se possa utilizar toda a cobertura
              contratada, após os prazos de carências contratuais. • No caso de CPT, haverá restrição de cobertura para cirurgias, leitos de alta
              tecnologia (UTI, unidade coronariana ou neonatal) e procedimentos de alta complexidade - PAC (tomografia, ressonância, etc*)
              EXCLUSIVAMENTE relacionados à doença ou lesão declarada, até 24 meses, contados desde a assina do contrato. Após o período
              máximo de 24 meses da assinatura contratual, a cobertura passará a ser integral de acordo com o plano contratado. • NÃO haverá
              restrição de cobertura para consultas médicas, internações não cirúrgicas, exames e procedimentos que não sejam de alta
              complexidade, mesmo que relacionadas à doença ou lesão preexistente declarada, desde que cumpridos os prazos de carências
              estabelecidas no contrato. • Não caberá alegação posterior de omissão de informação na Declaração de Saúde por parte da operadora
              para esta doença ou lesão.
              <br><br>
              3. AO NÃO DECLARAR AS DOENÇAS E/OU LESÕES QUE O BENEFICIÁRIO SAIBA SER PORTADOR NO MOMENTO DA
              CONTRATAÇÃO: • A operadora poderá suspeitar de omissão de informação e,neste caso, deverá comunicar imediatamente ao
              beneficiário, podendo oferecer CPT, ou solicitar abertura de processo administrativo junto à ANS, denunciando a omissão da
              informação. • Comprovada a omissão de informação pelo beneficiário, a operadora poderá RESCINDIR o contrato por FRAUDE e
              responsabilizá-lo pelos procedimentos referentes a doença ou lesão preexistente. • Até o julgamento final do processo pela ANS, NÃO
              poderá ocorrer suspensão do atendimento nem rescisão do contrato. Caso isto ocorra, encaminhe a denúncia à ANS.
              <br><br>
              ATENÇÃO! Se a operadora oferecer redução ou isenção de carência, isto não significa que dará cobertura assistencial para as
              doenças ou lesões que o beneficiário saiba ter no momento da assinatura contratual. Cobertura Parcial Temporária - CTP - NÃO é
              carência! Portanto, o beneficiário não deve deixar de informar se possui alguma doença ou lesão ao preencher a Declaração de Saúde!
              Para consultar a lista completa de procedimentos de alta complexidade PAC, acesse o Rol de Procedimentos e Eventos em Saúde da
              ANS do endereço eletrônico: www.ans.gov.br - Perfil Beneficiário. Em caso de dúvidas, entre em contato com a ANS pelo telefone
              0800-701-9656 ou consulte a página da ANS - www.ans.gov.br - Perfil Beneficiário.</div>';


$arquivo = "proposta.pdf";
$mpdf=new mPDF();
$mpdf->SetHTMLHeader($header_empresa);
$mpdf->SetHTMLFooter($footer);

$mpdf->WriteHTML($info_plano);
$mpdf->WriteHTML($dados_empresa);

if($operadora_escolhida == "CEMERU"){
    $mpdf->AddPage();
    $formulario_empresa = '<br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 50px;">
                          <b>Contato na empresa <font color="#d3d3d3">&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&</font> Proposta Nº: ' .$dados['id'].'</font></b></div>

                         <table border="1" cellspacing="0" style="width: 100%; font-size: 11px; margin-top: 10px;">
                           <tr>
                            <td> <b>Nome: </b>'.$dados['nome_contato_empresa'].' </td>
                            <td> <b>Telefone de contato: </b>'.$dados['telefone_contato_empresa'].' </td>
                           </tr>
                           <tr>
                            <td> <b>Cargo: </b>'.$dados['cargo_contato_empresa'].'</td>
                            <td> <b>Email: </b>'.$dados['email_contato_empresa'].'</td>
                           </tr>
                         </table>

                         <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 10px;">
                         <b>Tipo de Adesão <font color="red">(Preenchimento manual obrigatório)</font></b></div>

                         <table border="0" cellspacing="0" style="width: 100%; padding-top: 10px; font-size: 14px; text-align: center; margin-top: 0px;">
                           <tr>
                             <td>
                              <input type="checkbox"> <b>Total <font color="white">&&</font>
                              <input type="checkbox"> Diretores <font color="white">&&</font>
                              <input type="checkbox"> Gerentes <font color="white">&&</font>
                              <input type="checkbox"> Funcionários <font color="white">&&</font>
                              <input type="checkbox"> Estagiário / Jovem Aprendiz <font color="white">&&</font>
                            </td>
                           </tr>
                         </table>

                         <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 0px;">
                         <b>Inclusão de beneficiários <font color="red">(Preenchimento manual obrigatório)</font></b></div>

                         <table border="0" cellspacing="0" style="width: 100%; padding-top: 10px; font-size: 14px; margin-top: 5px;">
                           <tr>
                             <td>
                              <input type="checkbox"> <b>Titular <font color="white">&&</font>
                              <input type="checkbox"> Titular e Dependentes <font color="white">&&</font>
                              <input type="checkbox"> Titular, Dependentes e Agregados <font color="white">&&</font>
                            </td>
                           </tr>
                         </table>

                         <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 10px;">
                         <b>Dados do Corretor </b></div>

                         <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 10px;">
                           <tr>
                            <td colspan="2"> <b>Nome2: </b>'.$name.' </td>
                           </tr>
                           <tr>
                            <td> <b>CPF: </b>'.$cpf_final.'</td>
                            <td> <b>Telefone: </b>'.$user_tel.'</td>
                           </tr>
                           <tr>
                            <td colspan="2"> <b>Nome do distribuidor: </b> '.$dados['distribuidora'].'</td>
                           </tr>
                           <tr>
                            <td colspan="2"> <b>Nome do Gerente (Supervisor): </b> </td>
                           </tr>
                         </table>';

    $tabela;
     $produto_benefic = $dados_benefic['produto'];

     $faixa1_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 1 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa1_global = mysqli_query($conexao, $faixa1_query_global);
     $qtd_faixa1_global = mysqli_num_rows($exec_faixa1_global);

     $faixa1_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 1 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa1_dep_global = mysqli_query($conexao, $faixa1_dep_query_global);
     $qtd_faixa1_dep_global = mysqli_num_rows($exec_faixa1_dep_global);

     $total_faixa1_global = $qtd_faixa1_global + $qtd_faixa1_dep_global;

     ////////////////////// Faixa 2////////////////////////////////

     $faixa2_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 2 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa2_global = mysqli_query($conexao, $faixa2_query_global);
     $qtd_faixa2_global = mysqli_num_rows($exec_faixa2_global);

     $faixa2_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 2 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa2_dep_global = mysqli_query($conexao, $faixa2_dep_query_global);
     $qtd_faixa2_dep_global = mysqli_num_rows($exec_faixa2_dep_global);

     $total_faixa2_global = $qtd_faixa2_global + $qtd_faixa2_dep_global;

     ////////////////////// Faixa 3////////////////////////////////

     $faixa3_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 3 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa3_global = mysqli_query($conexao, $faixa3_query_global);
     $qtd_faixa3_global = mysqli_num_rows($exec_faixa3_global);

     $faixa3_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 3 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa3_dep_global = mysqli_query($conexao, $faixa3_dep_query_global);
     $qtd_faixa3_dep_global = mysqli_num_rows($exec_faixa3_dep_global);

     $total_faixa3_global = $qtd_faixa3_global + $qtd_faixa3_dep_global;

     ////////////////////// Faixa 4////////////////////////////////

     $faixa4_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 4 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa4_global = mysqli_query($conexao, $faixa4_query_global);
     $qtd_faixa4_global = mysqli_num_rows($exec_faixa4_global);

     $faixa4_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 4 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa4_dep_global = mysqli_query($conexao, $faixa4_dep_query_global);
     $qtd_faixa4_dep_global = mysqli_num_rows($exec_faixa4_dep_global);

     $total_faixa4_global = $qtd_faixa4_global + $qtd_faixa4_dep_global;

     ////////////////////// Faixa 5////////////////////////////////

     $faixa5_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 5 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa5_global = mysqli_query($conexao, $faixa5_query_global);
     $qtd_faixa5_global = mysqli_num_rows($exec_faixa5_global);

     $faixa5_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 5 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa5_dep_global = mysqli_query($conexao, $faixa5_dep_query_global);
     $qtd_faixa5_dep_global = mysqli_num_rows($exec_faixa5_dep_global);

     $total_faixa5_global = $qtd_faixa5_global + $qtd_faixa5_dep_global;

     ////////////////////// Faixa 6////////////////////////////////

     $faixa6_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 6 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa6_global = mysqli_query($conexao, $faixa6_query_global);
     $qtd_faixa6_global = mysqli_num_rows($exec_faixa6_global);

     $faixa6_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 6 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa6_dep_global = mysqli_query($conexao, $faixa6_dep_query_global);
     $qtd_faixa6_dep_global = mysqli_num_rows($exec_faixa6_dep_global);

     $total_faixa6_global = $qtd_faixa6_global + $qtd_faixa6_dep_global;

     ////////////////////// Faixa 7////////////////////////////////

     $faixa7_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 7 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa7_global = mysqli_query($conexao, $faixa7_query_global);
     $qtd_faixa7_global = mysqli_num_rows($exec_faixa7_global);

     $faixa7_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 7 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa7_dep_global = mysqli_query($conexao, $faixa7_dep_query_global);
     $qtd_faixa7_dep_global = mysqli_num_rows($exec_faixa7_dep_global);

     $total_faixa7_global = $qtd_faixa7_global + $qtd_faixa7_dep_global;

     ////////////////////// Faixa 8////////////////////////////////

     $faixa8_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 8 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa8_global = mysqli_query($conexao, $faixa8_query_global);
     $qtd_faixa8_global = mysqli_num_rows($exec_faixa8_global);

     $faixa8_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 8 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa8_dep_global = mysqli_query($conexao, $faixa8_dep_query_global);
     $qtd_faixa8_dep_global = mysqli_num_rows($exec_faixa8_dep_global);

     $total_faixa8_global = $qtd_faixa8_global + $qtd_faixa8_dep_global;

     ////////////////////// Faixa 9////////////////////////////////

     $faixa9_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 9 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa9_global = mysqli_query($conexao, $faixa9_query_global);
     $qtd_faixa9_global = mysqli_num_rows($exec_faixa9_global);

     $faixa9_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 9 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa9_dep_global = mysqli_query($conexao, $faixa9_dep_query_global);
     $qtd_faixa9_dep_global = mysqli_num_rows($exec_faixa9_dep_global);

     $total_faixa9_global = $qtd_faixa9_global + $qtd_faixa9_dep_global;

     ////////////////////// Faixa 10////////////////////////////////

     $faixa10_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 10 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa10_global = mysqli_query($conexao, $faixa10_query_global);
     $qtd_faixa10_global = mysqli_num_rows($exec_faixa10_global);

     $faixa10_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 10 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa10_dep_global = mysqli_query($conexao, $faixa10_dep_query_global);
     $qtd_faixa10_dep_global = mysqli_num_rows($exec_faixa10_dep_global);

     $total_faixa10_global = $qtd_faixa10_global + $qtd_faixa10_dep_global;

     //Total geral Produto Global//

     $total_geral_global = $total_faixa1_global+$total_faixa2_global+$total_faixa3_global+$total_faixa4_global+$total_faixa5_global+$total_faixa6_global+
     $total_faixa7_global+$total_faixa8_global+$total_faixa9_global+$total_faixa10_global;

     $faixa_preco1_global = mysqli_query($conexao, "SELECT * FROM tabela_precos where id = '$produto_benefic'");
     $faixa_preco1_exec_global = mysqli_fetch_array($faixa_preco1_global);
     $total_geral_preco_global =
     $faixa_preco1_exec_global['faixa1'] * $total_faixa1_global+
     $faixa_preco1_exec_global['faixa2'] * $total_faixa2_global+
     $faixa_preco1_exec_global['faixa3'] * $total_faixa3_global+
     $faixa_preco1_exec_global['faixa4'] * $total_faixa4_global+
     $faixa_preco1_exec_global['faixa5'] * $total_faixa5_global+
     $faixa_preco1_exec_global['faixa6'] * $total_faixa6_global+
     $faixa_preco1_exec_global['faixa7'] * $total_faixa7_global+
     $faixa_preco1_exec_global['faixa8'] * $total_faixa8_global+
     $faixa_preco1_exec_global['faixa9'] * $total_faixa9_global+
     $faixa_preco1_exec_global['faixa10'] * $total_faixa10_global;

     $var1 = (($faixa_preco1_exec_global['faixa2'] / $faixa_preco1_exec_global['faixa1']) * 100) -100;
     $var1_result_global = number_format($var1, 2, ',','.');

     $var2 = (($faixa_preco1_exec_global['faixa3'] / $faixa_preco1_exec_global['faixa2']) * 100) -100;
     $var2_result_global = number_format($var2, 2, ',','.');

     $var3 = (($faixa_preco1_exec_global['faixa4'] / $faixa_preco1_exec_global['faixa3']) * 100) -100;
     $var3_result_global = number_format($var3, 2, ',','.');

     $var4 = (($faixa_preco1_exec_global['faixa5'] / $faixa_preco1_exec_global['faixa4']) * 100) -100;
     $var4_result_global = number_format($var4, 2, ',','.');

     $var5 = (($faixa_preco1_exec_global['faixa6'] / $faixa_preco1_exec_global['faixa5']) * 100) -100;
     $var5_result_global = number_format($var5, 2, ',','.');

     $var6 = (($faixa_preco1_exec_global['faixa7'] / $faixa_preco1_exec_global['faixa6']) * 100) -100;
     $var6_result_global = number_format($var6, 2, ',','.');

     $var7 = (($faixa_preco1_exec_global['faixa8'] / $faixa_preco1_exec_global['faixa7']) * 100) -100;
     $var7_result_global = number_format($var7, 2, ',','.');

     $var8 = (($faixa_preco1_exec_global['faixa9'] / $faixa_preco1_exec_global['faixa8']) * 100) -100;
     $var8_result_global = number_format($var8, 2, ',','.');

     $var9 = (($faixa_preco1_exec_global['faixa10'] / $faixa_preco1_exec_global['faixa9']) * 100) -100;
     $var9_result_global = number_format($var9, 2, ',','.');

    $resumo_valores1 = '<div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 10px;">
                        <b>Resumo de valores </b></div>

                        <table border="1" cellspacing="0" style="width: 100%; text-align: center; font-size: 11px; margin-top: 10px;">
                          <tr>
                           <th width="100px"> FAIXA ETÁRIA </th>
                           <th> VARIAÇÃO DE <br>FAIXA ETÁRIA </th>
                           <th> QTD </th>
                           <th> VALOR INDIVIDUAL </th>
                           <th> VALOR TOTAL </th>
                          </tr>

                          <tr>
                           <td> 00 a 18 </td>
                           <td> 0% </td>
                           <td> '.$total_faixa1_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa1'], 2, '.', '').' </td>
                           <td>  R$ '.number_format($faixa_preco1_exec_global['faixa1'] * $total_faixa1_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> 19 a 23 </td>
                           <td> '.$var1_result_global.'% </td>
                           <td> '.$total_faixa2_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa2'], 2, '.', '').' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa2'] * $total_faixa2_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> 24 à 28 </td>
                           <td> '.$var2_result_global.'% </td>
                           <td> '.$total_faixa3_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa3'], 2, '.', '').'  </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa3'] * $total_faixa3_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> 29 à 33 </td>
                           <td> '.$var3_result_global.'% </td>
                           <td> '.$total_faixa4_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa4'], 2, '.', '').' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa4'] * $total_faixa4_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> 34 à 38 </td>
                           <td> '.$var4_result_global.'% </td>
                           <td> '.$total_faixa5_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa5'], 2, '.', '').' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa5'] * $total_faixa5_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> 39 à 43 </td>
                           <td> '.$var5_result_global.'% </td>
                           <td> '.$total_faixa6_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa6'], 2, '.', '').' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa6'] * $total_faixa6_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> 44 à 48 </td>
                           <td> '.$var6_result_global.'% </td>
                           <td> '.$total_faixa7_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa7'], 2, '.', '').' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa7'] * $total_faixa7_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> 49 à 53 </td>
                           <td> '.$var7_result_global.'% </td>
                           <td> '.$total_faixa8_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa8'], 2, '.', '').' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa8'] * $total_faixa8_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> 54 à 58 </td>
                           <td> '.$var8_result_global.'% </td>
                           <td> '.$total_faixa9_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa9'], 2, '.', '').' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa9'] * $total_faixa9_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> > 59 </td>
                           <td> '.$var9_result_global.'% </td>
                           <td> '.$total_faixa10_global.' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa10'], 2, '.', '').' </td>
                           <td> R$ '.number_format($faixa_preco1_exec_global['faixa10'] * $total_faixa10_global, 2, '.', '').' </td>
                          </tr>

                          <tr>
                           <td> <b>Total de benefíciários</b> </td>
                           <td> '.$var10_result_global.'% </td>
                           <td> '.$total_geral_global.' </td>
                           <td> <b>VALOR TOTAL</b> </td>
                           <td> R$ '.number_format($total_geral_preco_global, 2, '.', '').' </td>
                          </tr>
                        </table>

                        <div style="padding: 5px; margin-top: 9px; font-size: 13px; line-height: 1.5">
                        <b> VALOR TOTAL GERAL (MENSALIDADES) R$ '.number_format($total_geral_preco_global, 2, '.', '').' (_____________________________________________________
                        _________________________________________________________________________________________________________________)

                        <div style="text-align: center; color: red">(Preenchimento do valor obrigatório)</div>

                        <table cellspacing="-1" style="width: 100%; font-size: 12px; margin-top: 10px;">
                          <tr>
                           <td><center> __________________________________________________ </center></td>
                           <td><center> __________________________________________________ </center></td>
                          </tr>

                          <tr>
                           <td> <center>Local e data</center> </td>
                           <td> <center>Assinatura do representante legal da empresa</center> </td>
                          </tr>
                        </table>';

    $mpdf->WriteHTML($formulario_empresa);
    $mpdf->WriteHTML($resumo_valores1);

  } else if($operadora_escolhida == "VERTE"){
    $mpdf->AddPage();
    $formulario_empresa = '<br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 50px;">
                            <b>Contato na empresa <font color="#d3d3d3">&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&</font> Proposta Nº: ' .$dados['id'].'</font></b></div>

                             <table border="1" cellspacing="0" style="width: 100%; font-size: 10px; margin-top: 10px;">
                               <tr>
                                <td> <b>Nome: </b>'.$dados['nome_contato_empresa'].' </td>
                                <td> <b>Telefone de contato: </b>'.$dados['telefone_contato_empresa'].' </td>
                               </tr>
                               <tr>
                                <td> <b>Cargo: </b>'.$dados['cargo_contato_empresa'].'</td>
                                <td> <b>Email: </b>'.$dados['email_contato_empresa'].'</td>
                               </tr>
                             </table>

                             <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 10px;">
                             <b>Tipo de Adesão <font color="red">(Preenchimento manual obrigatório)</font></b></div>

                             <table border="0" cellspacing="0" style="width: 100%; padding-top: 10px; font-size: 12px; text-align: left; margin-top: 0px;">
                               <tr>
                                 <td>
                                  <input type="checkbox"> <b>Total <font color="white">&&</font>
                                  <input type="checkbox"> Diretores <font color="white">&&</font>
                                  <input type="checkbox"> Gerentes <font color="white">&&</font>
                                  <input type="checkbox"> Funcionários <font color="white">&&</font>
                                  <input type="checkbox"> Estagiário / Jovem Aprendiz <font color="white">&&</font>
                                </td>
                               </tr>
                             </table>

                             <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 5px;">
                             <b>Inclusão de beneficiários <font color="red">(Preenchimento manual obrigatório)</font></b></div>

                             <table border="0" cellspacing="0" style="width: 100%; padding-top: 10px; font-size: 12px; margin-top: 5px;">
                               <tr>
                                 <td>
                                  <input type="checkbox"> <b>Titular <font color="white">&&</font>
                                  <input type="checkbox"> Titular e Dependentes <font color="white">&&</font>
                                  <input type="checkbox"> Titular, Dependentes e Agregados <font color="white">&&</font>
                                </td>
                               </tr>
                             </table>

                             <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 7px;">
                             <b>Dados do Corretor </b></div>

                             <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 10px;">
                               <tr>
                                <td colspan="2"> <b>Nome3: </b>'.$name.' </td>
                               </tr>
                               <tr>
                                <td> <b>CPF: </b>'.$cpf_corretor_cadastrado.'</td>
                                <td> <b>Telefone: </b>'.$user_tel.'</td>
                               </tr>
                               <tr>
                                <td colspan="2"> <b>Nome do distribuidor:</b> '.$dados['distribuidora'].' </td>
                               </tr>
                               <tr>
                                <td colspan="2"> <b>Nome do Gerente (Supervisor): </b> </td>
                               </tr>
                             </table>

                             <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 10px;">
                             <b>Resumo de valores </b></div>';

    $mpdf->WriteHTML($formulario_empresa);

    $tabela;
     $produto_benefic = $dados_benefic['produto'];

     $faixa1_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 1 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa1_global = mysqli_query($conexao, $faixa1_query_global);
     $qtd_faixa1_global = mysqli_num_rows($exec_faixa1_global);

     $faixa1_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 1 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa1_dep_global = mysqli_query($conexao, $faixa1_dep_query_global);
     $qtd_faixa1_dep_global = mysqli_num_rows($exec_faixa1_dep_global);

     $total_faixa1_global = $qtd_faixa1_global + $qtd_faixa1_dep_global;

     ////////////////////// Faixa 2////////////////////////////////

     $faixa2_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 2 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa2_global = mysqli_query($conexao, $faixa2_query_global);
     $qtd_faixa2_global = mysqli_num_rows($exec_faixa2_global);

     $faixa2_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 2 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa2_dep_global = mysqli_query($conexao, $faixa2_dep_query_global);
     $qtd_faixa2_dep_global = mysqli_num_rows($exec_faixa2_dep_global);

     $total_faixa2_global = $qtd_faixa2_global + $qtd_faixa2_dep_global;

     ////////////////////// Faixa 3////////////////////////////////

     $faixa3_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 3 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa3_global = mysqli_query($conexao, $faixa3_query_global);
     $qtd_faixa3_global = mysqli_num_rows($exec_faixa3_global);

     $faixa3_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 3 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa3_dep_global = mysqli_query($conexao, $faixa3_dep_query_global);
     $qtd_faixa3_dep_global = mysqli_num_rows($exec_faixa3_dep_global);

     $total_faixa3_global = $qtd_faixa3_global + $qtd_faixa3_dep_global;

     ////////////////////// Faixa 4////////////////////////////////

     $faixa4_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 4 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa4_global = mysqli_query($conexao, $faixa4_query_global);
     $qtd_faixa4_global = mysqli_num_rows($exec_faixa4_global);

     $faixa4_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 4 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa4_dep_global = mysqli_query($conexao, $faixa4_dep_query_global);
     $qtd_faixa4_dep_global = mysqli_num_rows($exec_faixa4_dep_global);

     $total_faixa4_global = $qtd_faixa4_global + $qtd_faixa4_dep_global;

     ////////////////////// Faixa 5////////////////////////////////

     $faixa5_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 5 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa5_global = mysqli_query($conexao, $faixa5_query_global);
     $qtd_faixa5_global = mysqli_num_rows($exec_faixa5_global);

     $faixa5_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 5 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa5_dep_global = mysqli_query($conexao, $faixa5_dep_query_global);
     $qtd_faixa5_dep_global = mysqli_num_rows($exec_faixa5_dep_global);

     $total_faixa5_global = $qtd_faixa5_global + $qtd_faixa5_dep_global;

     ////////////////////// Faixa 6////////////////////////////////

     $faixa6_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 6 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa6_global = mysqli_query($conexao, $faixa6_query_global);
     $qtd_faixa6_global = mysqli_num_rows($exec_faixa6_global);

     $faixa6_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 6 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa6_dep_global = mysqli_query($conexao, $faixa6_dep_query_global);
     $qtd_faixa6_dep_global = mysqli_num_rows($exec_faixa6_dep_global);

     $total_faixa6_global = $qtd_faixa6_global + $qtd_faixa6_dep_global;

     ////////////////////// Faixa 7////////////////////////////////

     $faixa7_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 7 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa7_global = mysqli_query($conexao, $faixa7_query_global);
     $qtd_faixa7_global = mysqli_num_rows($exec_faixa7_global);

     $faixa7_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 7 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa7_dep_global = mysqli_query($conexao, $faixa7_dep_query_global);
     $qtd_faixa7_dep_global = mysqli_num_rows($exec_faixa7_dep_global);

     $total_faixa7_global = $qtd_faixa7_global + $qtd_faixa7_dep_global;

     ////////////////////// Faixa 8////////////////////////////////

     $faixa8_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 8 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa8_global = mysqli_query($conexao, $faixa8_query_global);
     $qtd_faixa8_global = mysqli_num_rows($exec_faixa8_global);

     $faixa8_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 8 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa8_dep_global = mysqli_query($conexao, $faixa8_dep_query_global);
     $qtd_faixa8_dep_global = mysqli_num_rows($exec_faixa8_dep_global);

     $total_faixa8_global = $qtd_faixa8_global + $qtd_faixa8_dep_global;

     ////////////////////// Faixa 9////////////////////////////////

     $faixa9_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 9 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa9_global = mysqli_query($conexao, $faixa9_query_global);
     $qtd_faixa9_global = mysqli_num_rows($exec_faixa9_global);

     $faixa9_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 9 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa9_dep_global = mysqli_query($conexao, $faixa9_dep_query_global);
     $qtd_faixa9_dep_global = mysqli_num_rows($exec_faixa9_dep_global);

     $total_faixa9_global = $qtd_faixa9_global + $qtd_faixa9_dep_global;

     ////////////////////// Faixa 10////////////////////////////////

     $faixa10_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 10 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa10_global = mysqli_query($conexao, $faixa10_query_global);
     $qtd_faixa10_global = mysqli_num_rows($exec_faixa10_global);

     $faixa10_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 10 and proposta = '$proposta' and produto = '$produto_benefic'";
     $exec_faixa10_dep_global = mysqli_query($conexao, $faixa10_dep_query_global);
     $qtd_faixa10_dep_global = mysqli_num_rows($exec_faixa10_dep_global);

     $total_faixa10_global = $qtd_faixa10_global + $qtd_faixa10_dep_global;

     //Total geral Produto Global//

     $total_geral_global = $total_faixa1_global+$total_faixa2_global+$total_faixa3_global+$total_faixa4_global+$total_faixa5_global+$total_faixa6_global+
     $total_faixa7_global+$total_faixa8_global+$total_faixa9_global+$total_faixa10_global;

     $faixa_preco1_global = mysqli_query($conexao, "SELECT * FROM tabela_precos where id = '$produto_benefic'");
     $faixa_preco1_exec_global = mysqli_fetch_array($faixa_preco1_global);
     $total_geral_preco_global =
     $faixa_preco1_exec_global['faixa1'] * $total_faixa1_global+
     $faixa_preco1_exec_global['faixa2'] * $total_faixa2_global+
     $faixa_preco1_exec_global['faixa3'] * $total_faixa3_global+
     $faixa_preco1_exec_global['faixa4'] * $total_faixa4_global+
     $faixa_preco1_exec_global['faixa5'] * $total_faixa5_global+
     $faixa_preco1_exec_global['faixa6'] * $total_faixa6_global+
     $faixa_preco1_exec_global['faixa7'] * $total_faixa7_global+
     $faixa_preco1_exec_global['faixa8'] * $total_faixa8_global+
     $faixa_preco1_exec_global['faixa9'] * $total_faixa9_global+
     $faixa_preco1_exec_global['faixa10'] * $total_faixa10_global;

     $var1 = (($faixa_preco1_exec_global['faixa2'] / $faixa_preco1_exec_global['faixa1']) * 100) -100;
     $var1_result_global = number_format($var1, 2, ',','.');

     $var2 = (($faixa_preco1_exec_global['faixa3'] / $faixa_preco1_exec_global['faixa2']) * 100) -100;
     $var2_result_global = number_format($var2, 2, ',','.');

     $var3 = (($faixa_preco1_exec_global['faixa4'] / $faixa_preco1_exec_global['faixa3']) * 100) -100;
     $var3_result_global = number_format($var3, 2, ',','.');

     $var4 = (($faixa_preco1_exec_global['faixa5'] / $faixa_preco1_exec_global['faixa4']) * 100) -100;
     $var4_result_global = number_format($var4, 2, ',','.');

     $var5 = (($faixa_preco1_exec_global['faixa6'] / $faixa_preco1_exec_global['faixa5']) * 100) -100;
     $var5_result_global = number_format($var5, 2, ',','.');

     $var6 = (($faixa_preco1_exec_global['faixa7'] / $faixa_preco1_exec_global['faixa6']) * 100) -100;
     $var6_result_global = number_format($var6, 2, ',','.');

     $var7 = (($faixa_preco1_exec_global['faixa8'] / $faixa_preco1_exec_global['faixa7']) * 100) -100;
     $var7_result_global = number_format($var7, 2, ',','.');

     $var8 = (($faixa_preco1_exec_global['faixa9'] / $faixa_preco1_exec_global['faixa8']) * 100) -100;
     $var8_result_global = number_format($var8, 2, ',','.');

     $var9 = (($faixa_preco1_exec_global['faixa10'] / $faixa_preco1_exec_global['faixa9']) * 100) -100;
     $var9_result_global = number_format($var9, 2, ',','.');

    $resumo_valores = '<table border="1" cellspacing="0" style="width: 100%; text-align: center; font-size: 10px; margin-top: -40px;">
                        <tr>
                         <th width="100px"> FAIXA ETÁRIA </th>
                         <th> VARIAÇÃO DE <br>FAIXA ETÁRIA </th>
                         <th> QTD </th>
                         <th> VALOR INDIVIDUAL </th>
                         <th> VALOR TOTAL </th>
                        </tr>

                        <tr>
                         <td> 00 a 18 </td>
                         <td> 0% </td>
                         <td> '.$total_faixa1_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa1'], 2, ',','.').' </td>
                         <td>  R$ '.number_format($faixa_preco1_exec_global['faixa1'] * $total_faixa1_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> 19 a 23 </td>
                         <td> '.$var1_result_global.'% </td>
                         <td> '.$total_faixa2_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa2'], 2, ',','.').' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa2'] * $total_faixa2_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> 24 à 28 </td>
                         <td> '.$var2_result_global.'% </td>
                         <td> '.$total_faixa3_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa3'], 2, ',','.').'  </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa3'] * $total_faixa3_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> 29 à 33 </td>
                         <td> '.$var3_result_global.'% </td>
                         <td> '.$total_faixa4_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa4'], 2, ',','.').' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa4'] * $total_faixa4_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> 34 à 38 </td>
                         <td> '.$var4_result_global.'% </td>
                         <td> '.$total_faixa5_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa5'], 2, ',','.').' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa5'] * $total_faixa5_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> 39 à 43 </td>
                         <td> '.$var5_result_global.'% </td>
                         <td> '.$total_faixa6_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa6'], 2, ',','.').' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa6'] * $total_faixa6_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> 44 à 48 </td>
                         <td> '.$var6_result_global.'% </td>
                         <td> '.$total_faixa7_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa7'], 2, ',','.').' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa7'] * $total_faixa7_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> 49 à 53 </td>
                         <td> '.$var7_result_global.'% </td>
                         <td> '.$total_faixa8_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa8'], 2, ',','.').' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa8'] * $total_faixa8_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> 54 à 58 </td>
                         <td> '.$var8_result_global.'% </td>
                         <td> '.$total_faixa9_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa9'], 2, ',','.').' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa9'] * $total_faixa9_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> > 59 </td>
                         <td> '.$var9_result_global.'% </td>
                         <td> '.$total_faixa10_global.' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa10'], 2, ',','.').' </td>
                         <td> R$ '.number_format($faixa_preco1_exec_global['faixa10'] * $total_faixa10_global, 2, ',','.').' </td>
                        </tr>

                        <tr>
                         <td> <b>Total de benefíciários</b> </td>
                         <td> '.$var10_result_global.'% </td>
                         <td> '.$total_geral_global.' </td>
                         <td> <b>Valor total do contato</b> </td>
                         <td> R$ '.number_format($total_geral_preco_global, 2, ',','.').' </td>
                        </tr>
                      </table>';

    $tabela_franquia = '<table border="1" cellspacing="0" style="width: 100%; margin-left: 30px; text-align: center; font-size: 10px; margin-top: -30px;">
                          <tr>
                           <th>Valores de Franquia</th>
                           <td>VERTE SAÚDE</td>
                           <td>Rede <br>Credenciada</td>
                          </tr>

                          <tr>
                           <td>Consultas</td>
                           <td>Isento</td>
                           <td>R$ 18,00</td>
                          </tr>

                          <tr>
                           <td>Fisioterapia</td>
                           <td colspan="2">R$ 6,00</td>
                          </tr>

                          <tr>
                           <td>Terapia Ocupacional</td>
                           <td colspan="2">R$ 10,00</td>
                          </tr>

                          <tr>
                           <td>Fonoaudiologia</td>
                           <td colspan="2">R$ 10,00</td>
                          </tr>

                          <tr>
                           <td>Psicologia</td>
                           <td colspan="2">R$ 12,00</td>
                          </tr>

                          <tr>
                           <td>Nutrição</td>
                           <td colspan="2">R$ 10,00</td>
                          </tr>

                          <tr>
                           <td>Internação Psiquiátrica</td>
                           <td colspan="2">R$ 70,00</td>
                          </tr>

                          <tr>
                           <td>Petscan</td>
                           <td colspan="2">R$ 500,00</td>
                          </tr>

                          <tr>
                           <td>Acunpuntura</td>
                           <td colspan="2">R$ 9,00</td>
                          </tr>

                          <tr>
                           <td>Mamografia</td>
                           <td colspan="2">R$ 14,00</td>
                          </tr>

                          <tr>
                           <td>Tomografia</td>
                           <td colspan="2">R$ 40,00</td>
                          </tr>

                          <tr>
                           <td>Ressonância</td>
                           <td colspan="2">R$ 80,00</td>
                          </tr>

                          <tr>
                           <td>Câmara Hiperbárica</td>
                           <td colspan="2">R$ 80,00</td>
                          </tr>
                        </table>';

                      $assinatura = '<div style="padding: 5px; margin-top: 4px; font-size: 13px; line-height: 1.5">
                      <b> VALOR TOTAL GERAL (MENSALIDADES) R$ '.number_format($total_geral_preco_global, 2, '.', '').' (_____________________________________________________
                      _________________________________________________________________________________________________________________)

                      <div style="text-align: center; color: red">(Preenchimento do valor obrigatório)</div>

                      <table cellspacing="-1" style="width: 100%; font-size: 12px; margin-top: 5px;">
                        <tr>
                         <td><center> __________________________________________________ </center></td>
                         <td><center> __________________________________________________ </center></td>
                        </tr>

                        <tr>
                         <td> <center>Local e data</center> </td>
                         <td> <center>Assinatura do representante legal da empresa</center> </td>
                        </tr>
                      </table>';

    $tabelas = '<br><div style="padding: 5px; font-size: 14px; border-radius: 4px; margin-top: 0px;"></div>
                  <table>
                    <tr>
                      <td> '.$resumo_valores.' </td>
                      <td> '.$tabela_franquia.' </td>
                    </tr>
                  </table>';

    $mpdf->WriteHTML($tabelas);
    $mpdf->WriteHTML($assinatura);

   } else if($operadora_escolhida == "LIFEDAY"){
     $mpdf->AddPage();
     $formulario_empresa = '<br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 50px;">
                          <b>Contato na empresa <font color="#d3d3d3">&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&</font> Proposta Nº: ' .$dados['id'].'</b></div>

                          <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 10px;">
                            <tr>
                             <td> <b>Nome: </b>'.$dados['nome_contato_empresa'].' </td>
                             <td> <b>Telefone de contato: </b>'.$dados['telefone_contato_empresa'].' </td>
                            </tr>
                            <tr>
                             <td> <b>Cargo: </b>'.$dados['cargo_contato_empresa'].'</td>
                             <td> <b>Email: </b>'.$dados['email_contato_empresa'].'</td>
                            </tr>
                          </table>

                          <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 10px;">
                          <b>Tipo de Adesão <font color="red">(Preenchimento manual obrigatório)</font></b></div>

                          <table border="0" cellspacing="0" style="width: 100%; padding-top: 10px; font-size: 12px; text-align: left; margin-top: 0px;">
                            <tr>
                              <td>
                               <input type="checkbox"> <b>Total <font color="white">&&</font>
                               <input type="checkbox"> Diretores <font color="white">&&</font>
                               <input type="checkbox"> Gerentes <font color="white">&&</font>
                               <input type="checkbox"> Funcionários <font color="white">&&</font>
                               <input type="checkbox"> Estagiário / Jovem Aprendiz <font color="white">&&</font>
                             </td>
                            </tr>
                          </table>

                          <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 10px;">
                          <b>Inclusão de beneficiários <font color="red">(Preenchimento manual obrigatório)</font></b></div>

                          <table border="0" cellspacing="0" style="width: 100%; padding-top: 10px; font-size: 12px; margin-top: 0px;">
                            <tr>
                              <td>
                               <input type="checkbox"> <b>Titular <font color="white">&&</font>
                               <input type="checkbox"> Titular e Dependentes <font color="white">&&</font>
                               <input type="checkbox"> Titular, Dependentes e Agregados <font color="white">&&</font>
                             </td>
                            </tr>
                          </table>

                          <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 10px;">
                          <b>Dados do Corretor </b></div>

                          <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 10px;">
                            <tr>
                             <td colspan="2"> <b>Nome4: </b>'.$nome_corretor_cadastrado.' </td>
                            </tr>
                            <tr>
                             <td> <b>CPF: </b>'.$cpf_corretor_cadastrado.'</td>
                             <td> <b>Telefone: </b>'.$telefone_corretor_cadastrado.'</td>
                            </tr>
                            <tr>
                             <td colspan="2"> <b>Nome do distribuidor: '.$dados['distribuidora'].'</b> </td>
                            </tr>
                            <tr>
                             <td colspan="2"> <b>Nome do Gerente (Supervisor): </b> </td>
                            </tr>
                          </table>

                          <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 10px;">
                          <b>Resumo de valores </b></div>';
    $mpdf->WriteHTML($formulario_empresa);

     ////////////////////// Faixa 2////////////////////////////////

    $tabela1;
     $faixa1_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 1 and proposta = '$proposta' and produto = 4";
     $exec_faixa1_global = mysqli_query($conexao, $faixa1_query_global);
     $qtd_faixa1_global = mysqli_num_rows($exec_faixa1_global);

     $faixa1_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 1 and proposta = '$proposta' and produto = 4";
     $exec_faixa1_dep_global = mysqli_query($conexao, $faixa1_dep_query_global);
     $qtd_faixa1_dep_global = mysqli_num_rows($exec_faixa1_dep_global);

     $total_faixa1_global = $qtd_faixa1_global + $qtd_faixa1_dep_global;

     ////////////////////// Faixa 2////////////////////////////////

     $faixa2_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 2 and proposta = '$proposta' and produto = 4";
     $exec_faixa2_global = mysqli_query($conexao, $faixa2_query_global);
     $qtd_faixa2_global = mysqli_num_rows($exec_faixa2_global);

     $faixa2_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 2 and proposta = '$proposta' and produto = 4";
     $exec_faixa2_dep_global = mysqli_query($conexao, $faixa2_dep_query_global);
     $qtd_faixa2_dep_global = mysqli_num_rows($exec_faixa2_dep_global);

     $total_faixa2_global = $qtd_faixa2_global + $qtd_faixa2_dep_global;

     ////////////////////// Faixa 3////////////////////////////////

     $faixa3_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 3 and proposta = '$proposta' and produto = 4";
     $exec_faixa3_global = mysqli_query($conexao, $faixa3_query_global);
     $qtd_faixa3_global = mysqli_num_rows($exec_faixa3_global);

     $faixa3_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 3 and proposta = '$proposta' and produto = 4";
     $exec_faixa3_dep_global = mysqli_query($conexao, $faixa3_dep_query_global);
     $qtd_faixa3_dep_global = mysqli_num_rows($exec_faixa3_dep_global);

     $total_faixa3_global = $qtd_faixa3_global + $qtd_faixa3_dep_global;

     ////////////////////// Faixa 4////////////////////////////////

     $faixa4_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 4 and proposta = '$proposta' and produto = 4";
     $exec_faixa4_global = mysqli_query($conexao, $faixa4_query_global);
     $qtd_faixa4_global = mysqli_num_rows($exec_faixa4_global);

     $faixa4_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 4 and proposta = '$proposta' and produto = 4";
     $exec_faixa4_dep_global = mysqli_query($conexao, $faixa4_dep_query_global);
     $qtd_faixa4_dep_global = mysqli_num_rows($exec_faixa4_dep_global);

     $total_faixa4_global = $qtd_faixa4_global + $qtd_faixa4_dep_global;

     ////////////////////// Faixa 5////////////////////////////////

     $faixa5_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 5 and proposta = '$proposta' and produto = 4";
     $exec_faixa5_global = mysqli_query($conexao, $faixa5_query_global);
     $qtd_faixa5_global = mysqli_num_rows($exec_faixa5_global);

     $faixa5_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 5 and proposta = '$proposta' and produto = 4";
     $exec_faixa5_dep_global = mysqli_query($conexao, $faixa5_dep_query_global);
     $qtd_faixa5_dep_global = mysqli_num_rows($exec_faixa5_dep_global);

     $total_faixa5_global = $qtd_faixa5_global + $qtd_faixa5_dep_global;

     ////////////////////// Faixa 6////////////////////////////////

     $faixa6_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 6 and proposta = '$proposta' and produto = 4";
     $exec_faixa6_global = mysqli_query($conexao, $faixa6_query_global);
     $qtd_faixa6_global = mysqli_num_rows($exec_faixa6_global);

     $faixa6_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 6 and proposta = '$proposta' and produto = 4";
     $exec_faixa6_dep_global = mysqli_query($conexao, $faixa6_dep_query_global);
     $qtd_faixa6_dep_global = mysqli_num_rows($exec_faixa6_dep_global);

     $total_faixa6_global = $qtd_faixa6_global + $qtd_faixa6_dep_global;

     ////////////////////// Faixa 7////////////////////////////////

     $faixa7_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 7 and proposta = '$proposta' and produto = 4";
     $exec_faixa7_global = mysqli_query($conexao, $faixa7_query_global);
     $qtd_faixa7_global = mysqli_num_rows($exec_faixa7_global);

     $faixa7_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 7 and proposta = '$proposta' and produto = 4";
     $exec_faixa7_dep_global = mysqli_query($conexao, $faixa7_dep_query_global);
     $qtd_faixa7_dep_global = mysqli_num_rows($exec_faixa7_dep_global);

     $total_faixa7_global = $qtd_faixa7_global + $qtd_faixa7_dep_global;

     ////////////////////// Faixa 8////////////////////////////////

     $faixa8_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 8 and proposta = '$proposta' and produto = 4";
     $exec_faixa8_global = mysqli_query($conexao, $faixa8_query_global);
     $qtd_faixa8_global = mysqli_num_rows($exec_faixa8_global);

     $faixa8_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 8 and proposta = '$proposta' and produto = 4";
     $exec_faixa8_dep_global = mysqli_query($conexao, $faixa8_dep_query_global);
     $qtd_faixa8_dep_global = mysqli_num_rows($exec_faixa8_dep_global);

     $total_faixa8_global = $qtd_faixa8_global + $qtd_faixa8_dep_global;

     ////////////////////// Faixa 9////////////////////////////////

     $faixa9_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 9 and proposta = '$proposta' and produto = 4";
     $exec_faixa9_global = mysqli_query($conexao, $faixa9_query_global);
     $qtd_faixa9_global = mysqli_num_rows($exec_faixa9_global);

     $faixa9_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 9 and proposta = '$proposta' and produto = 4";
     $exec_faixa9_dep_global = mysqli_query($conexao, $faixa9_dep_query_global);
     $qtd_faixa9_dep_global = mysqli_num_rows($exec_faixa9_dep_global);

     $total_faixa9_global = $qtd_faixa9_global + $qtd_faixa9_dep_global;

     ////////////////////// Faixa 10////////////////////////////////

     $faixa10_query_global = "SELECT * FROM wp_beneficiario where faixa_idade = 10 and proposta = '$proposta' and produto = 4";
     $exec_faixa10_global = mysqli_query($conexao, $faixa10_query_global);
     $qtd_faixa10_global = mysqli_num_rows($exec_faixa10_global);

     $faixa10_dep_query_global = "SELECT * FROM wp_dependente where faixa_idade = 10 and proposta = '$proposta' and produto = 4";
     $exec_faixa10_dep_global = mysqli_query($conexao, $faixa10_dep_query_global);
     $qtd_faixa10_dep_global = mysqli_num_rows($exec_faixa10_dep_global);

     $total_faixa10_global = $qtd_faixa10_global + $qtd_faixa10_dep_global;

     //Total geral Produto Global//

     $total_geral_global = $total_faixa1_global+$total_faixa2_global+$total_faixa3_global+$total_faixa4_global+$total_faixa5_global+$total_faixa6_global+
     $total_faixa7_global+$total_faixa8_global+$total_faixa9_global+$total_faixa10_global;

     if($total_geral_global <= 5){
       $faixa_preco1_global = mysqli_query($conexao, "SELECT * FROM tabela_precos where id = 4");
     } else if($total_geral_global >= 6 && $total_geral_global <= 29){
       $faixa_preco1_global = mysqli_query($conexao, "SELECT * FROM tabela_precos where id = 6");
     } else if ($total_geral_global >= 30 && $total_geral_global <= 99){
       $faixa_preco1_global = mysqli_query($conexao, "SELECT * FROM tabela_precos where id = 7");
     }

     $faixa_preco1_exec_global = mysqli_fetch_array($faixa_preco1_global);
     $total_geral_preco_global =
     $faixa_preco1_exec_global['faixa1'] * $total_faixa1_global+
     $faixa_preco1_exec_global['faixa2'] * $total_faixa2_global+
     $faixa_preco1_exec_global['faixa3'] * $total_faixa3_global+
     $faixa_preco1_exec_global['faixa4'] * $total_faixa4_global+
     $faixa_preco1_exec_global['faixa5'] * $total_faixa5_global+
     $faixa_preco1_exec_global['faixa6'] * $total_faixa6_global+
     $faixa_preco1_exec_global['faixa7'] * $total_faixa7_global+
     $faixa_preco1_exec_global['faixa8'] * $total_faixa8_global+
     $faixa_preco1_exec_global['faixa9'] * $total_faixa9_global+
     $faixa_preco1_exec_global['faixa10'] * $total_faixa10_global;

     $var1 = (($faixa_preco1_exec_global['faixa2'] / $faixa_preco1_exec_global['faixa1']) * 100) -100;
     $var1_result_global = number_format($var1, 2, ',','.');

     $var2 = (($faixa_preco1_exec_global['faixa3'] / $faixa_preco1_exec_global['faixa2']) * 100) -100;
     $var2_result_global = number_format($var2, 2, ',','.');

     $var3 = (($faixa_preco1_exec_global['faixa4'] / $faixa_preco1_exec_global['faixa3']) * 100) -100;
     $var3_result_global = number_format($var3, 2, ',','.');

     $var4 = (($faixa_preco1_exec_global['faixa5'] / $faixa_preco1_exec_global['faixa4']) * 100) -100;
     $var4_result_global = number_format($var4, 2, ',','.');

     $var5 = (($faixa_preco1_exec_global['faixa6'] / $faixa_preco1_exec_global['faixa5']) * 100) -100;
     $var5_result_global = number_format($var5, 2, ',','.');

     $var6 = (($faixa_preco1_exec_global['faixa7'] / $faixa_preco1_exec_global['faixa6']) * 100) -100;
     $var6_result_global = number_format($var6, 2, ',','.');

     $var7 = (($faixa_preco1_exec_global['faixa8'] / $faixa_preco1_exec_global['faixa7']) * 100) -100;
     $var7_result_global = number_format($var7, 2, ',','.');

     $var8 = (($faixa_preco1_exec_global['faixa9'] / $faixa_preco1_exec_global['faixa8']) * 100) -100;
     $var8_result_global = number_format($var8, 2, ',','.');

     $var9 = (($faixa_preco1_exec_global['faixa10'] / $faixa_preco1_exec_global['faixa9']) * 100) -100;
     $var9_result_global = number_format($var9, 2, ',','.');

    $resumo_valores1 = '<table border="1" cellspacing="0" style="width: 100%; text-align: center; font-size: 10px; margin-top: -35px;">
                         <tr>
                          <th colspan="5" style="font-size: 13px;"> Global </th>
                         </tr>

                         <tr>
                          <th>FAIXA <br>ETÁRIA</th>
                          <th>% F. ETÁRIA</th>
                          <th>QTD</th>
                          <th width="115px">V. INDIVIDUAL</th>
                          <th width="65px">V. TOTAL</th>
                         </tr>

                         <tr>
                          <td> 00 a 18 </td>
                          <td> 0% </td>
                          <td> '.$total_faixa1_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa1'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa1'] * $total_faixa1_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> 19 a 23 </td>
                          <td> '.$var1_result_global.' %</td>
                          <td> '.$total_faixa2_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa2'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa2'] * $total_faixa2_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> 24 à 28 </td>
                          <td> '.$var2_result_global.' %</td>
                          <td> '.$total_faixa3_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa3'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa3'] * $total_faixa3_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> 29 à 33 </td>
                          <td> '.$var3_result_global.'% </td>
                          <td> '.$total_faixa4_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa4'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa4'] * $total_faixa4_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> 34 à 38 </td>
                          <td> '.$var4_result_global.' % </td>
                          <td> '.$total_faixa5_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa5'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa5'] * $total_faixa5_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> 39 à 43 </td>
                          <td> '.$var5_result_global.' % </td>
                          <td> '.$total_faixa6_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa6'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa6'] * $total_faixa6_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> 44 à 48 </td>
                          <td> '.$var6_result_global.' % </td>
                          <td> '.$total_faixa7_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa7'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa7'] * $total_faixa7_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> 49 à 53 </td>
                          <td> '.$var7_result_global.' % </td>
                          <td> '.$total_faixa8_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa8'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa8'] * $total_faixa8_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> 54 à 58 </td>
                          <td> '.$var8_result_global.' % </td>
                          <td> '.$total_faixa9_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa9'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa9'] * $total_faixa9_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> > 59 </td>
                          <td> '.$var9_result_global.' % </td>
                          <td> '.$total_faixa10_global.' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa10'], 2, ',','.').' </td>
                          <td> R$ '.number_format($faixa_preco1_exec_global['faixa10'] * $total_faixa10_global, 2, ',','.').' </td>
                         </tr>

                         <tr>
                          <td> </td>
                          <td> <b>Total</b> </td>
                          <td> '.$total_geral_global.' </td>
                          <td width="80px"> <b>SUBTOTAL</b> </td>
                          <td> R$ '.number_format($total_geral_preco_global, 2, ',','.').' </td>
                         </tr>
                       </table>';

     $faixa1_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 1 and proposta = '$proposta' and produto = 5";
     $exec_faixa1_amb  = mysqli_query($conexao, $faixa1_query_amb);
     $qtd_faixa1_amb  = mysqli_num_rows($exec_faixa1_amb);

     $faixa1_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 1 and proposta = '$proposta' and produto = 5";
     $exec_faixa1_dep_amb = mysqli_query($conexao, $faixa1_dep_query_amb);
     $qtd_faixa1_dep_amb = mysqli_num_rows($exec_faixa1_dep_amb);

     $total_faixa1_amb = $qtd_faixa1_amb + $qtd_faixa1_dep_amb;

     ////////////////////// Faixa 2////////////////////////////////

     $faixa2_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 2 and proposta = '$proposta' and produto = 5";
     $exec_faixa2_amb = mysqli_query($conexao, $faixa2_query_amb);
     $qtd_faixa2_amb = mysqli_num_rows($exec_faixa2_amb);

     $faixa2_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 2 and proposta = '$proposta' and produto = 5";
     $exec_faixa2_dep_amb = mysqli_query($conexao, $faixa2_dep_query_amb);
     $qtd_faixa2_dep_amb = mysqli_num_rows($exec_faixa2_dep_amb);

     $total_faixa2_amb = $qtd_faixa2_amb + $qtd_faixa2_dep_amb;

     ////////////////////// Faixa 3////////////////////////////////

     $faixa3_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 3 and proposta = '$proposta' and produto = 5";
     $exec_faixa3_amb = mysqli_query($conexao, $faixa3_query_amb);
     $qtd_faixa3_amb = mysqli_num_rows($exec_faixa3_amb);

     $faixa3_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 3 and proposta = '$proposta' and produto = 5";
     $exec_faixa3_dep_amb = mysqli_query($conexao, $faixa3_dep_query_amb);
     $qtd_faixa3_dep_amb = mysqli_num_rows($exec_faixa3_dep_amb);

     $total_faixa3_amb = $qtd_faixa3_amb + $qtd_faixa3_dep_amb;

     ////////////////////// Faixa 4////////////////////////////////

     $faixa4_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 4 and proposta = '$proposta' and produto = 5";
     $exec_faixa4_amb = mysqli_query($conexao, $faixa4_query_amb);
     $qtd_faixa4_amb = mysqli_num_rows($exec_faixa4_amb);

     $faixa4_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 4 and proposta = '$proposta' and produto = 5";
     $exec_faixa4_dep_amb = mysqli_query($conexao, $faixa4_dep_query_amb);
     $qtd_faixa4_dep_amb = mysqli_num_rows($exec_faixa4_dep_amb);

     $total_faixa4_amb = $qtd_faixa4_amb + $qtd_faixa4_dep_amb;

     ////////////////////// Faixa 5////////////////////////////////

     $faixa5_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 5 and proposta = '$proposta' and produto = 5";
     $exec_faixa5_amb = mysqli_query($conexao, $faixa5_query_amb);
     $qtd_faixa5_amb = mysqli_num_rows($exec_faixa5_amb);

     $faixa5_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 5 and proposta = '$proposta' and produto = 5";
     $exec_faixa5_dep_amb = mysqli_query($conexao, $faixa5_dep_query_amb);
     $qtd_faixa5_dep_amb = mysqli_num_rows($exec_faixa5_dep_amb);

     $total_faixa5_amb = $qtd_faixa5_amb + $qtd_faixa5_dep_amb;

     ////////////////////// Faixa 6////////////////////////////////

     $faixa6_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 6 and proposta = '$proposta' and produto = 5";
     $exec_faixa6_amb = mysqli_query($conexao, $faixa6_query_amb);
     $qtd_faixa6_amb = mysqli_num_rows($exec_faixa6_amb);

     $faixa6_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 6 and proposta = '$proposta' and produto = 5";
     $exec_faixa6_dep_amb = mysqli_query($conexao, $faixa6_dep_query_amb);
     $qtd_faixa6_dep_amb = mysqli_num_rows($exec_faixa6_dep_amb);

     $total_faixa6_amb = $qtd_faixa6_amb + $qtd_faixa6_dep_amb;

     ////////////////////// Faixa 7////////////////////////////////

     $faixa7_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 7 and proposta = '$proposta' and produto = 5";
     $exec_faixa7_amb = mysqli_query($conexao, $faixa7_query_amb);
     $qtd_faixa7_amb = mysqli_num_rows($exec_faixa7_amb);

     $faixa7_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 7 and proposta = '$proposta' and produto = 5";
     $exec_faixa7_dep_amb = mysqli_query($conexao, $faixa7_dep_query_amb);
     $qtd_faixa7_dep_amb = mysqli_num_rows($exec_faixa7_dep_amb);

     $total_faixa7_amb = $qtd_faixa7_amb + $qtd_faixa7_dep_amb;

     ////////////////////// Faixa 8////////////////////////////////

     $faixa8_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 8 and proposta = '$proposta' and produto = 5";
     $exec_faixa8_amb = mysqli_query($conexao, $faixa8_query_amb);
     $qtd_faixa8_amb = mysqli_num_rows($exec_faixa8_amb);

     $faixa8_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 8 and proposta = '$proposta' and produto = 5";
     $exec_faixa8_dep_amb = mysqli_query($conexao, $faixa8_dep_query_amb);
     $qtd_faixa8_dep_amb = mysqli_num_rows($exec_faixa8_dep_amb);

     $total_faixa8_amb = $qtd_faixa8_amb + $qtd_faixa8_dep_amb;

     ////////////////////// Faixa 9////////////////////////////////

     $faixa9_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 9 and proposta = '$proposta' and produto = 5";
     $exec_faixa9_amb = mysqli_query($conexao, $faixa9_query_amb);
     $qtd_faixa9_amb = mysqli_num_rows($exec_faixa9_amb);

     $faixa9_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 9 and proposta = '$proposta' and produto = 5";
     $exec_faixa9_dep_amb = mysqli_query($conexao, $faixa9_dep_query_amb);
     $qtd_faixa9_dep_amb = mysqli_num_rows($exec_faixa9_dep_amb);

     $total_faixa9_amb = $qtd_faixa9_amb + $qtd_faixa9_dep_amb;

     ////////////////////// Faixa 10////////////////////////////////

     $faixa10_query_amb = "SELECT * FROM wp_beneficiario where faixa_idade = 10 and proposta = '$proposta' and produto = 5";
     $exec_faixa10_amb = mysqli_query($conexao, $faixa10_query_amb);
     $qtd_faixa10_amb = mysqli_num_rows($exec_faixa10_amb);

     $faixa10_dep_query_amb = "SELECT * FROM wp_dependente where faixa_idade = 10 and proposta = '$proposta' and produto = 5";
     $exec_faixa10_dep_amb = mysqli_query($conexao, $faixa10_dep_query_amb);
     $qtd_faixa10_dep_amb = mysqli_num_rows($exec_faixa10_dep_amb);

     $total_faixa10_amb = $qtd_faixa10_amb + $qtd_faixa10_dep_amb;

     //Total geral Produto Global//

     $total_geral_amb = $total_faixa1_amb+$total_faixa2_amb+$total_faixa3_amb+$total_faixa4_amb+$total_faixa5_amb+$total_faixa6_amb+
     $total_faixa7_amb+$total_faixa8_amb+$total_faixa9_amb+$total_faixa10_amb;

     if($total_geral_amb <= 5){
       $faixa_preco1_amb = mysqli_query($conexao, "SELECT * FROM tabela_precos where id = 5");
     } else if($total_geral_amb >= 6 && $total_geral_amb <= 29){
       $faixa_preco1_global = mysqli_query($conexao, "SELECT * FROM tabela_precos where id = 8");
     } else if ($total_geral_amb >= 30 && $total_geral_amb <= 99){
       $faixa_preco1_amb = mysqli_query($conexao, "SELECT * FROM tabela_precos where id = 9");
     }

     $tabela2;

      $faixa_preco1_exec_amb = mysqli_fetch_array($faixa_preco1_amb);
      $total_geral_preco_amb =
      $faixa_preco1_exec_amb['faixa1'] * $total_faixa1_amb+
      $faixa_preco1_exec_amb['faixa2'] * $total_faixa2_amb+
      $faixa_preco1_exec_amb['faixa3'] * $total_faixa3_amb+
      $faixa_preco1_exec_amb['faixa4'] * $total_faixa4_amb+
      $faixa_preco1_exec_amb['faixa5'] * $total_faixa5_amb+
      $faixa_preco1_exec_amb['faixa6'] * $total_faixa6_amb+
      $faixa_preco1_exec_amb['faixa7'] * $total_faixa7_amb+
      $faixa_preco1_exec_amb['faixa8'] * $total_faixa8_amb+
      $faixa_preco1_exec_amb['faixa9'] * $total_faixa9_amb+
      $faixa_preco1_exec_amb['faixa10'] * $total_faixa10_amb;

     $var1_amb = (($faixa_preco1_exec_amb['faixa2'] / $faixa_preco1_exec_amb['faixa1']) * 100) -100;
     $var1_result_amb = number_format($var1_amb, 2, ',','.');

     $var2_amb = (($faixa_preco1_exec_amb['faixa3'] / $faixa_preco1_exec_amb['faixa2']) * 100) -100;
     $var2_result_amb = number_format($var2_amb, 2, ',','.');

     $var3_amb = (($faixa_preco1_exec_amb['faixa4'] / $faixa_preco1_exec_amb['faixa3']) * 100) -100;
     $var3_result_amb = number_format($var3_amb, 2, ',','.');

     $var4_amb = (($faixa_preco1_exec_amb['faixa5'] / $faixa_preco1_exec_amb['faixa4']) * 100) -100;
     $var4_result_amb = number_format($var4_amb, 2, ',','.');

     $var5_amb = (($faixa_preco1_exec_amb['faixa6'] / $faixa_preco1_exec_amb['faixa5']) * 100) -100;
     $var5_result_amb = number_format($var5_amb, 2, ',','.');

     $var6_amb = (($faixa_preco1_exec_amb['faixa7'] / $faixa_preco1_exec_amb['faixa6']) * 100) -100;
     $var6_result_amb = number_format($var6_amb, 2, ',','.');

     $var7_amb = (($faixa_preco1_exec_amb['faixa8'] / $faixa_preco1_exec_amb['faixa7']) * 100) -100;
     $var7_result_amb = number_format($var7_amb, 2, ',','.');

     $var8_amb = (($faixa_preco1_exec_amb['faixa9'] / $faixa_preco1_exec_amb['faixa8']) * 100) -100;
     $var8_result_amb = number_format($var8_amb, 2, ',','.');

     $var9_amb = (($faixa_preco1_exec_amb['faixa10'] / $faixa_preco1_exec_amb['faixa9']) * 100) -100;
     $var9_result_amb = number_format($var9_amb, 2, ',','.');

     $valor_total = $total_geral_preco_amb + $total_geral_preco_global;

     $resumo_valores2 = '<table border="1" cellspacing="0" style="width: 100%; text-align: center; font-size: 10px; margin-top: -35px;">
                         <tr>
                          <th colspan="5" style="font-size: 13px;"> AMBULATORIAL </th>
                         </tr>

                         <tr>
                          <th>FAIXA <br>ETÁRIA</th>
                          <th>% F. ETÁRIA</th>
                          <th>QTD</th>
                          <th width="115px">V. INDIVIDUAL</th>
                          <th width="65px">V. TOTAL</th>
                         </tr>

                   <tr>
                    <td> 00 a 18 </td>
                    <td> 0% </td>
                    <td> '.$total_faixa1_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa1'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa1'] * $total_faixa1_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> 19 a 23 </td>
                    <td> '.$var1_result_amb.'% </td>
                    <td> '.$total_faixa2_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa2'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa2'] * $total_faixa2_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> 24 à 28 </td>
                    <td> '.$var2_result_amb.'% </td>
                    <td> '.$total_faixa3_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa3'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa3'] * $total_faixa3_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> 29 à 33 </td>
                    <td> '.$var3_result_amb.'% </td>
                    <td> '.$total_faixa4_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa4'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa4'] * $total_faixa4_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> 34 à 38 </td>
                    <td> '.$var4_result_amb.'% </td>
                    <td> '.$total_faixa5_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa5'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa5'] * $total_faixa5_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> 39 à 43 </td>
                    <td> '.$var5_result_amb.'% </td>
                    <td> '.$total_faixa6_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa6'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa6'] * $total_faixa6_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> 44 à 48 </td>
                    <td> '.$var6_result_amb.'% </td>
                    <td> '.$total_faixa7_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa7'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa7'] * $total_faixa7_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> 49 à 53 </td>
                    <td> '.$var7_result_amb.'% </td>
                    <td> '.$total_faixa8_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa8'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa8'] * $total_faixa8_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> 54 à 58 </td>
                    <td> '.$var8_result_amb.'% </td>
                    <td> '.$total_faixa9_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa9'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa9'] * $total_faixa9_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> > 59 </td>
                    <td> '.$var9_result_amb.'% </td>
                    <td> '.$total_faixa10_amb.' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa10'], 2, ',','.').' </td>
                    <td> R$ '.number_format($faixa_preco1_exec_amb['faixa10'] * $total_faixa10_amb, 2, ',','.').' </td>
                   </tr>

                   <tr>
                    <td> </td>
                    <td> <b>Total</b> </td>
                    <td> '.$total_geral_amb.' </td>
                    <td width="115px"> <b>SUBTOTAL</b> </td>
                    <td> R$'.number_format($total_geral_preco_amb, 2, ',','.').' </td>
                   </tr>
                 </table>';

              $assinatura = '<div style="padding: 5px; margin-top: 4px; font-size: 13px; line-height: 1.5">
              <b> VALOR TOTAL GERAL (MENSALIDADES) R$ '.number_format($valor_total, 2, '.', '').' (_____________________________________________________
              _________________________________________________________________________________________________________________)

              <div style="text-align: center; color: red">(Preenchimento do valor obrigatório)</div>

              <table cellspacing="-1" style="width: 100%; font-size: 12px; margin-top: 5px;">
                <tr>
                 <td><center> __________________________________________________ </center></td>
                 <td><center> __________________________________________________ </center></td>
                </tr>

                <tr>
                 <td> <center>Local e data</center> </td>
                 <td> <center>Assinatura do representante legal da empresa</center> </td>
                </tr>
              </table>';

          $global_amb = '<br><div style="padding: 5px; font-size: 14px; border-radius: 4px; margin-top: 10px;"></div>
             <table>
               <tr>
                 <td> '.$resumo_valores1.' </td>
                 <td> '.$resumo_valores2.' </td>
               </tr>
             </table>';

    $mpdf->WriteHTML($global_amb);
    $mpdf->WriteHTML($assinatura);

    $mpdf->AddPage();
    $copart = '<br><div style="margin-top: 55px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; font-size: 14px; text-align: center; padding: 5px;">
                <b><font color="white">&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&</font> Proposta Nº: ' .$dados['id'].'</b></div>
                <br><div style="margin-top: 0px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; font-size: 17px; text-align: center; padding: 5px; background-color: #d3d3d3">
                <b>Tabela de Coparticipação</b></div>
                <style> td{height: 22px;} checkbox{width: 120px;}</style>
                <table border="1" cellspacing="0" style="width: 100%; font-size: 14px; text-align:center; 12px; margin-top: 20px;">
                  <tr>
                    <th width="270px"> PROCEDIMENTO </th>
                    <th> COPARTICIPAÇÕES </th>
                    <th> LIMITE DE COBRANÇA </th>
                  </tr>
                  <tr>
                    <td width="300px"> Consulta Eletiva </td>
                    <td> R$ 25,00 </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Consulta de Emergência/Urgência </td>
                    <td> R$ 25,00 </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Exames Básicos </td>
                    <td> Isento </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Exames Especiais </td>
                    <td> Isento </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Procedimentos Básicos </td>
                    <td> Isento </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Procedimentos Especiais </td>
                    <td> Isento </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Psicoterapia </td>
                    <td> R$ 25,00 </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Fonoaudiologia </td>
                    <td> R$ 25,00 </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Fisioterapia </td>
                    <td> Isento </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Nutrição </td>
                    <td> R$ 25,00 </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Terapia Ocupacional </td>
                    <td> R$ 25,00 </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Quimioterapia </td>
                    <td> 45% do procedimento </td>
                    <td> R$ 300,00 </td>
                  </tr>

                  <tr>
                    <td> Diálise e Hemodiálise </td>
                    <td> 40% do procedimento </td>
                    <td> R$ 150,00 </td>
                  </tr>

                  <tr>
                    <td> Radioterapia </td>
                    <td> 45% do procedimento </td>
                    <td> R$ 300,00 </td>
                  </tr>

                  <tr>
                    <td> Parto a termo </td>
                    <td> Isento </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Internações </td>
                    <td> Isento </td>
                    <td> Isento </td>
                  </tr>

                  <tr>
                    <td> Doenças/Lesões Preexistentes </td>
                    <td> Isento </td>
                    <td> Isento </td>
                  </tr>
                </table>';
    $mpdf->WriteHTML($copart);

  }

  $declara_termos = '<br><div style="margin-top: 50px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; font-size: 14px; text-align: left; padding: 5px; background-color: #d3d3d3">
                    <b><font color="#d3d3d3">&</font>Declaração de Saúde <font color="#d3d3d3">&&&&&&&&&&&&&&&&&h&&&&&&&&&&&&&&&</font><b> Proposta Nº: ' .$dados['id'].'</b></div>
                    <div style="margin-top: 0;"> <br><b>Concordo com as seguintes informações adicionais:</b>
                    <ul>Tenho ciência e estou de pleno acordo de que o início da vigênca do contrato seguirá a seguinte regra:
                    O início e cobertura do plano ocorrerão com a quitação do 1º boleto bancário, conforme data escolhida.</ul>
                    <ul>O período de movimentação cadastral, para efeito de faturamento, deverá ser realizado em até 20 dias
                    antes do vencimento da próxima fatura.</ul>

                    <br><b>Caso a proposta seja efetivada, selecione abaixo a melhor opção que convier ao solicitante:</b>

                    <div style="margin-top: 15px;"><input type="checkbox"> Tenho ciência que o acesso à rede médica do meu contrato será disponibilizado através do site da operadora
                    e do manual do associado que será entregue juntamente com o cartão de identificação do beneficiário.<br>
                    <input type="checkbox"> Desejo receber as futuras cobranças através de e-mail.

                    <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 20px;">
                    <b>Declaração de termos e responsabilidades específicas</b></div><br>

                    <b>Declaro para todos os fins e efeitos que:<br> </b><br>
                    1) Tenho ciência e estou de acordo com as condições gerais e prazos de carência constantes, não tendo
                    qualquer dúvida com relação à sua aplicação, cabendo à Contém estabelecer as reduções desses
                    prazos, mediante aos documentos apresentados no ato da contratação e autorizados pela Operadora;<br>
                    2) Assumo a responsabilidade pelas declarações feitas por mim, livre e espontaneamente, e na
                    qualidade de responsável pelos beneficiários incluídos nesta proposta, assumo a obrigação pelo
                    pagamento das demais obrigações integrantes do plano que agora subscrevo;<br>
                    3) Tenho ciência de que este documento e suas cópias não poderão ter rasuras, motivo pelo qual sei
                    que a Contém poderá não aceitá-lo, sendo motivo para preenchimento de uma nova proposta de
                    contratação;<br>
                    4) Tenho ciência da existência e disponibilidade do plano Referência, quarto coletivo, com abrangência
                    Nacional, e que ele a mim foi oferecido, sendo minha a opção pela contratação do plano a que se refere
                    esta proposta;<br>
                    5) Tenho ciência de que será de responsabilidade da empresa contratante entregar ao beneficiário
                    titular, previamente à assinatura do contrato de adesão, o Manual de Orientação para Contratação de
                    Planos de Saúde (MPS);<br>
                    6) Estou ciente de que será de responsabilidade da Contém o envio ao beneficiário titular do Guia de
                    Leitura Contratual (GLC), junto com o cartão de identificação;<br>
                    7) Estou ciente de que o vencimento da segunda mensalidade ocorrerá em 30 dias após a data da
                    vigência do contrato.';

  $mpdf->AddPage();
  $mpdf->WriteHTML($declara_termos);

  $beneficiarios_dados = mysqli_query($conexao, "SELECT * FROM wp_beneficiario where proposta = '$proposta'");

    while($listar_benefic = mysqli_fetch_array($beneficiarios_dados)) {
      $mpdf->SetHTMLHeader($header_benefic);
      $mpdf->AddPage();

      $cpf_titular = $listar_benefic['cpf'];
      $dependentes_dados = mysqli_query($conexao, "SELECT * FROM wp_dependente where cpf_titular = '$cpf_titular'");
      $qtd_dep_barra = mysqli_num_rows($dependentes_dados);

      $beneficiarios = '<br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top:50px;">
                          <font color="#d3d3d3">&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&</font><b> Proposta Nº: ' .$dados['id'].'</b></div>
                          <div style="margin-top: 15px; padding: 0px;">
                            <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 0px;">
                              <tr>
                               <td> <b>CNPJ: </b>'.$dados['cnpj'].' </td>
                               <td width="450px"> <b>Nome da empresa: </b>'.$dados['nome_fantasia'].'</td>
                              </tr>
                            </table>

                            <br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px;"> <b>Titular (Sem abreviações)</b></div>

                            <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 20px;">
                            <tr>
                              <td colspan="3"> <b>Nome Completo: </b>'.$listar_benefic['nome'].' </td>
                            </tr>
                            <tr>
                              <td colspan="3"> <b>Nome da Mãe: </b>'.$listar_benefic['nome_mae'].' </td>
                            </tr>
                            <tr>
                              <td> <b>Data de Nascimento: </b>'.$listar_benefic['nascimento'].' </td>
                              <td> <b>Sexo: </b>'.$listar_benefic['sexo'].' </td>
                              <td> <b>Estado Civil: </b>'.$listar_benefic['estado_civil'].' </td>
                            </tr>
                            <tr>
                              <td colspan="3"> <b>Naturalidade: </b>'.$listar_benefic['naturalidade'].' </td>
                            </tr>
                            <tr>
                              <td> <b>CPF: </b>'.$listar_benefic['cpf'].' </td>
                              <td> <b>RG: </b>'.$listar_benefic['rg'].' </td>
                              <td> <b>Orgão Expedidor: </b>'.$listar_benefic['orgao'].' </td>
                            </tr>
                            <tr>
                              <td colspan="3"> <b>Logradouro: </b>'.$listar_benefic['rua'].', '.$listar_benefic['numero'].' </td>
                            </tr>
                            <tr>
                              <td> <b>Cidade: </b>'.$listar_benefic['cidade'].' </td>
                              <td colspan="2"> <b>Bairro: </b>'.$listar_benefic['bairro'].' </td>
                            </tr>

                            <tr>
                              <td> <b>CEP: </b>'.$listar_benefic['cep'].' </td>
                              <td> <b>UF: </b>'.$listar_benefic['uf'].' </td>
                              <td> <b>Email: </b>'.$listar_benefic['email'].' </td>
                            </tr>
                            <tr>
                              <td colspan="3"> <b>Cartão do SUS: </b>'.$listar_benefic['sus'].' </td>
                            </tr>
                            <tr>
                              <td colspan="3"> <b>Telefone Residencial: </b>'.$listar_benefic['tel_res'].' </td>
                            </tr>
                            <tr>
                              <td colspan="3"> <b>Telefone Celular: </b>'.$listar_benefic['tel_cel'].' </td>
                            </tr>
                          </table>';

                          if($qtd_dep_barra > 0){
                            $beneficiarios .= '<br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px;"> <b>Dependentes (Sem abreviações)</b></div>';
                          }
                          while($listar_benefic2 = mysqli_fetch_array($dependentes_dados)) {

                            switch ($listar_benefic2['parentesco']) {
                              case 1:
                                   $parentesco_switch = 'TITULAR';
                                   break;
                              case 2:
                                    $parentesco_switch = 'AGREGADO(A)';
                                    break;
                              case 3:
                                    $parentesco_switch = 'COMPANHEIRO(A)';
                                    break;
                              case 4:
                                    $parentesco_switch = 'CÔNJUGUE(A)';
                                    break;
                              case 5:
                                    $parentesco_switch = 'FILHO(A)';
                                    break;
                              case 6:
                                    $parentesco_switch = 'FILHO ADOTIVO(A)';
                                    break;
                              case 7:
                                   $parentesco_switch = 'IRMÃO(Ã)';
                                   break;
                              case 8:
                                    $parentesco_switch = 'MÃE';
                                    break;
                              case 9:
                                    $parentesco_switch = 'PAI';
                                    break;
                              case 10:
                                    $parentesco_switch = 'NETO(A)';
                                    break;
                              case 11:
                                    $parentesco_switch = 'SOBRINHO(A)';
                                    break;
                              case 12:
                                    $parentesco_switch = 'SOGRO';
                                    break;
                              case 13:
                                   $parentesco_switch = 'SOGRA';
                                   break;
                              case 14:
                                    $parentesco_switch = 'ENTEADO';
                                    break;
                              case 15:
                                    $parentesco_switch = 'GENRO';
                                    break;
                              case 16:
                                    $parentesco_switch = 'NORA';
                                    break;
                              case 17:
                                    $parentesco_switch = 'CUNHADO';
                                    break;
                              case 18:
                                    $parentesco_switch = 'PRIMO(A)';
                                    break;
                              case 19:
                                    $parentesco_switch = 'AVÔ';
                                    break;
                              case 20:
                                    $parentesco_switch = 'AVÓ';
                                    break;
                            }

                            $beneficiarios .= '<br><div style="margin-top: 0px; padding: 0px;">
                                                <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 0px;">
                                                  <tr>
                                                    <td colspan="2"> <b>'.$indice.'Nome Completo: </b>'.$listar_benefic2['nome'].' </td>
                                                    <td colspan="2"> <b>Nome da Mãe: </b>'.$listar_benefic2['nome_mae'].'</td>
                                                  </tr>
                                                  <tr>
                                                    <td> <b>Nascimento: </b>'.$listar_benefic2['nascimento'].'</td>
                                                    <td> <b>Sexo: </b>'.$listar_benefic2['sexo'].'</td>
                                                    <td> <b>Estado Civil: </b>'.$listar_benefic2['estado_civil'].'</td>
                                                    <td> <b>CPF: </b>'.$listar_benefic2['cpf'].'</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2"> <b>Parentesco: </b>'.$parentesco_switch.'</td>
                                                    <td> <b>CNS: </b>'.$listar_benefic2['cns'].'</td>
                                                    <td> <b>DNV: </b>'.$listar_benefic2['dnv'].'</td>
                                                  </tr>
                                                </table>';
                          }
      $mpdf->WriteHTML($beneficiarios);
      $mpdf->AddPage();

      $carta_dados = '<table style="width: 100%;" class="borda">'.
        '<tr>
          <td>

          <table style="padding: 0px; border:1px solid; font-size: 12px; margin-top: 25px; width: 100%;">
            <tr>
              <td>
                <center><b> Beneficiário </b></center><br>
              </td>
              <tr>
                <td>
                  Local:____________________________,
                  Data:____/____/________
                </td>
              </tr>
              <tr>
                <td>
                  <div align="left">Nome: '.$listar_benefic['nome'].'
                </td>
              </tr>
              <tr>
                <td>
                  <div align="left">CPF: '.$listar_benefic['cpf'].'
                </td>
              </tr>
              <tr>
                <td>
                  <div align="left">Assinatura: ______________________________________________
                </td>
              </tr>
              <tr>
                <td>
                  <center> <div style="font-size: 9px;">Nome legível, assinatura e CPF </div></center>
                </td>
              </tr>
              </td>
            </tr>
          </table>

           </td>

           <td>

            <table style="padding: 0px; border:1px solid; font-size: 12px; margin-top: 25px; width: 100%;">

            <tr>
              <td>
                <center><b> Intermediário Operadora / Beneficiário </b></center><br>
              </td>
              <tr>
                <td>
                  Local:____________________________,
                  Data:____/____/________
                </td>
              </tr>
              <tr>
                <td>
                  <div align="left">Nome: '.$nome_corretor_cadastrado.'
                </td>
              </tr>
              <tr>
                <td>
                  <div align="left">CPF: '.$cpf_corretor_cadastrado.'
                </td>
              </tr>
              <tr>
                <td>
                  <div align="left">Assinatura: ______________________________________________
                </td>
              </tr>
              <tr>
                <td>
                  <center> <div style="font-size: 9px;">Nome legível, assinatura e CPF </div></center>
                </td>
              </tr>
              </td>
            </tr>

            </table>

          </td>
        </tr>
       </table>';
      $mpdf->WriteHTML($carta);
      $mpdf->WriteHTML($carta_dados);

      $mpdf->AddPage();
      $declara_saude = '<br><div style="margin-top: 50px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; font-size: 12px; text-align: left; padding: 5px; background-color: #d3d3d3">
                        <b><font color="#d3d3d3">&</font>Declaração de Saúde <font color="#d3d3d3">&&&&&&&&&&h&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&</font><b> Proposta Nº: ' .$dados['id'].'</b></div>

                        <table border="1" cellspacing="0" style="width: 100%; font-size: 9px; margin-top: 2px;">
                          <tr>
                            <th> Beneficiário </th>
                            <th> Nascimento </th>
                            <th> Peso </th>
                            <th> Altura </th>
                          </tr>

                          <tr>
                            <td> <b>'.$listar_benefic['nome'].' </b></td>
                            <td> <b>'.$listar_benefic['nascimento'].' </b></td>
                            <td> </td>
                            <td> </td>
                          </tr>';

                        $cpf_titular2 = $listar_benefic['cpf'];
                        $dependentes_dados2 = mysqli_query($conexao, "SELECT * FROM wp_dependente where cpf_titular = '$cpf_titular2'");

                        while($listar_benefic3 = mysqli_fetch_array($dependentes_dados2)) {
                          $declara_saude .= '<tr>
                                                <td> <b>'.$listar_benefic3['nome'].' </b></td>
                                                <td> <b>'.$listar_benefic3['nascimento'].' </b></td>
                                                <td>  </td>
                                                <td>  </td>
                                              </tr>';
                        }
                        $declara_saude .= '</table> <div style="font-size: 9px; margin-top: 10px;">
                                           <b>IMPORTANTE:</b> O preenchimento desta declaração é obrigatória para habilitação no plano de saúde.
                                           Você tem a opção de ser orientado(a) sem ônus financeiro, por um médico indicado pela operadora ou
                                           por um de sua confiança, caso em que as despesas com honorários serão de sua responsabilidade.</div>

                                          <table border="1" cellspacing="0" style="width: 100%; font-size: 10px; margin-top: 15px;">
                                             <tr>
                                               <th width="15px"> </th>
                                               <th> Todas as questões deverão ser respondidas com "SIM" ou "NÃO" </th>
                                               <th width="20px"> T </th>
                                               <th width="20px"> D1 </th>
                                               <th width="20px"> D2 </th>
                                               <th width="20px"> D3 </th>
                                               <th width="20px"> D4 </th>
                                             </tr>';

                              $id_operadora = $dados_operadora['id'];
                              $perguntas = mysqli_query($conexao, "SELECT * FROM declaracao_saude where operadora = '$id_operadora'");
                              $indice2 = 1;

                              while($listar_perguntas = mysqli_fetch_array($perguntas)) {
                                $declara_saude .= '<tr>
                                                    <td width="15px"><center> '.$indice2.' </center></th>
                                                    <td> '.$listar_perguntas['pergunta'].' </th>
                                                    <td> </th>
                                                    <td> </th>
                                                    <td> </th>
                                                    <td> </th>
                                                    <td> </th>
                                                  </tr>';

                                  $indice2++;
                              }

                              $declara_saude .= '</table>
                              <div style="font-size: 13px; text-align: right; margin-top: 20px;">
                              __________________________________
                              <div style="margin-top: 6px; font-size: 10px;">
                              RÚBRICA DO BENEFICIÁRIO TITULAR';
      $mpdf->WriteHTML($declara_saude);

      $mpdf->AddPage();
      $declara_saude_pessoal = '<br><div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; border-radius: 4px; margin-top: 50px;">
                        <font color="#d3d3d3">&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&</font><b> Proposta Nº: ' .$dados['id'].'</b></div>
                        <br><div style="margin-top: -10px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; font-size: 17px; text-align: center; padding: 5px; background-color: #d3d3d3">
                        <b>Declaração pessoal de Saúde</b></div>
                        <style> td{height: 22px;} checkbox{width: 120px;}</style>
                        <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 10px;">
                          <tr>
                            <th> ITEM </th>
                            <th> BENEFICIÁRIO </th>
                            <th> DATA DO EVENTO </th>
                            <th> DESCRIÇÃO </th>
                          </tr>
                          <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                          </tr>
                        </table>
                        <div style="margin-top: 15px; font-size: 13px">Para o preenchimento desta declaração de saúde, declaro que:
                        <br><br>
                        <input type="checkbox"> Fui orientado por um médico da '.$dados['operadora'].'.<br>
                        <input type="checkbox"> Fui orientado pelo meu médico particular, não credenciado à '.$dados['operadora'].'.<br>
                        <input type="checkbox"> Tenho conhecimento de todas as perguntas acima expostas, razão pela qual dispensei a
                        orientação médica, assumindo a responsabilidade por todas as informações prestadas.<br><br>

                        <div style="font-size: 13px; text-align: justify; margin-top: 10px;">
                        Declaro que as informações prestadas são verdadeiras e completas, estando ciente de que a omissão de informação sobre a doença e/ou
                        lesão preexistente poderá acarretar a abertura de processo administrativo junto à ANS, bem como a rescisão contratual e a responsa do
                        beneficiário nos termos do artigo 13 da lei nº 9.656/98.<br><br>

                        Declaro estar ciente de que:<br><br>

                        Cobertura Parcial Temporária(CTP) é aquela que admite, por um período interrupto de até 24 meses, a partir da data de contratação
                        ou adesão ao plano privado de assistência à saúde, a  suspensão da cobertura de Procedimentos de Alta complexidade (PAC), leitos de alta
                        tecnologia e procedimentos cirúrgicos, desde que relacionados exclusivamente às doenças ou lesões preexistentes declaradas pelo beneficiário
                        ou seu representante legal;<br><br>

                        Agravo é qualquer acréscimo no valor da contraprestração paga ao plano privado de assistência à saúde, para que
                        o beneficiário tenha direito integral à cobertura contratada, para a doença ou lesão preexistente declarada, após os
                        prazos de carências contratuais, de acordo com as condições negociadas entre a operadora e o beneficiário.<br><br>

                        Assim, quanto às doenças ou lesões preexistentes declaradas opto pela(o):<br><br>
                        <b><input type="checkbox"> Cobertura Parcial Temporária</b>

                        <div style="font-size: 13px; text-align: center; margin-top: 40px;">
                        Local e Data: _______________________________________, _______de____________________de 20_____<br><br>
                        ________________________________________________________
                        <div style="margin-top: 8px; font-size: 10px;">
                        ASSINATURA DO BENEFICIÁRIO TITULAR';
      $mpdf->WriteHTML($declara_saude_pessoal);
    }
    $mpdf->SetHTMLHeader($header_empresa);
  

    if($dados['operadora'] == 'VERTE') {
        $condicoes_gerais1 = '<br><div style="margin-top: 55px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; font-size: 14px; text-align: left; padding: 5px; background-color: #d3d3d3">
                           <b>Condições Gerais</b></div><br>
                           <b> DAS CONDIÇÕES GERAIS </b>
  
                           <br><br>
                            <div style="text-align: justify; font-size: 13px;">
                            <b>1.</b> Pelo presente instrumento particular, CONTÉM ADMINISTRADORA DE PLANOS DE SAÚDE LTDA,
                            utilizando como nome fantasia CONTÉM , inscrita no CNPJ/MF sob nº 13.286.268/0001-83 e na ANS sob
                            nº 41832-3, situada na Rua do Carmo, nº 08 - 10º andar, Centro - Rio de Janeiro/RJ - CEP 20011-020,
                            denominada neste ato ESTIPULANTE; e ASSOCIAÇÃO DOS FUNCIONÁRIOS PÚBLICOS DO ESTADO DO
                            RIO GRANDE DO SUL, utilizando como nome fantasia VERTE SAÚDE - AFPERGS, inscrita no CNPJ/MF
                            sob nº 92.741.016/0001-73 e na ANS sob nº 41759-9, situada na Rua dos Andradas, nº 846, Centro Histórico,
                            Porto Alegre - RS - CEP 90020-006, denominada neste ato OPERADORA, vêm celebrar o presente
                            Contrato de Adesão na modalidade Coletivo Empresarial, de forma a permitir que os sócios, executivos e
                            empregados da EMPRESA CONTRATANTE, bem como seus respectivos dependentes, possam usufruir da
                            cobertura médico-assistencial prevista neste Contrato, em conformidade com as Resoluções Normativas
                            nº 195 e 196 da Agência Nacional de Saúde Suplementar/ANS.<br><br>
  
                            <b>DO OBJETO DO CONTRATO </b><br><br>
  
                            <b>1.1</b> O objeto deste Contrato é pactuar a adesão de Pessoa Jurídica, permitindo a ela, consequentemente,
                            proceder a inclusão das pessoas físicas a ela vinculadas por relação empregatícia ou estatutária para que
                            tenham cobertura à prestação continuada de serviços e/ou cobertura de custos assistenciais na forma de
                            Plano Privado de Assistência à Saúde prevista no Inciso I, do Art 1º da Leiº 9.656/98, visando a assistência
                            médica hospitalar com a cobertura de todas as doenças da “Classificação Estatística Internacional de
                            Doenças e Problemas com a Saúde”, da Organização Mundial de Saúde/OMS, obedecendo o “Rol de
                            Procedimentos e Eventos em Saúde” editado pela Agência Nacional de Saúde Suplementar/ANS, vigente
                            à época do evento.<br><br>
  
                            <b>DOS BENEFICIÁRIOS DO CONTRATO</b><br><br>
  
                            <b>2.</b> Pessoas aptas a utilizar os serviços<br><br>
                            O Plano de Saúde Coletivo Empresarial é aquele que oferece cobertura da atenção prestada à população
                            delimitada e vinculada à EMPRESA CONTRATANTE, por relação empregatícia ou estatutária.
  
                            2.1 Serão considerados BENEFICIÁRIOS da prestação de serviços o titular, pertencente ao corpo funcional
                            da EMPRESA CONTRATANTE, podendo ainda, serem inscritos pelo titular seus dependentes, assim
                            constituídos:<br><br>
  
                            a) o cônjuge ou companheiro(a) havendo união estável na forma da lei, do mesmo sexo ou do sexo oposto;<br>
                            b) os filhos, os netos (naturais e/ou adotivos) até 35 (trinta e cinco) anos de idade incompletos (34 anos,
                            11 meses e 29 dias) ou de qualquer idade se inválidos física ou mentalmente em caráter permanente,<br>
                            mediante comprovação de incapacidade;<br>
                            c) os tutelados(as) e curatelados(as), menor sob guarda com o respectivo termo de tutela e curatela ou
                            guarda nos limites etários definidos nesta cláusula.<br><br>
  
                            <b>2.2</b> Também serão considerados BENEFICIÁRIOS da prestação de serviços o titular, pertencente ao corpo
                            funcional da EMPRESA CONTRATANTE, podendo ainda, serem inscritos pelo titular seus dependentes,
                            assim constituídos:<br><br>
  
                            a) os sócios da EMPRESA CONTRATANTE
                            b) os Administradores da EMPRESA CONTRATANTE<br>
                            c) os demitidos ou aposentados que tenham sido vinculados anteriormente à pessoa jurídica<br>
                            d) os trabalhadores temporários;<br>
                            e) os estagiários e menores aprendizes.<br></div>';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais1);
  
      $condicoes_gerais2 .=  '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              <b>2.3</b> A adesão do grupo familiar dependerá da participação do titular no plano de saúde.
                              <br><b>2.4</b> Cadastramento de BENEFICIÁRIOS:<br>
  
                              Para uso dos benefícios previstos neste Contrato é indispensável o cadastramento prévio dos
                              BENEFICIÁRIOS titulares e dependentes junto à ADMINISTRADORA e à OPERADORA.<br>
  
                              <b>2.4.1</b> Haverá cobertura contratual ao recém-nascido, filho natural ou adotivo do beneficiário, ou do seu
                              dependente, durante os primeiros 30 (trinta) dias após o parto ou adoção, independente do cadastramento,
                              sendo condicionadas as carências já cumpridas pelo beneficiário titular, sendo vedada qualquer alegação
                              de DLP ou aplicação de CPT ou Agravo;<br>
  
                              <b>2.4.2</b> No caso de inscrição do recém-nascido (filho natural ou adotivo) do beneficiário, dentro do prazo
                              máximo de 30 (trinta) dias do nascimento/adoção, este ingressará no plano com as mesmas carências já
                              cumpridas até a data da inscrição pelo titular;<br>
  
                              <b>2.4.3</b> No caso de inscrição de filho adotivo até 12 anos de idade haverá aproveitamento das carências já
                              cumpridas pelo beneficiário adotante;<br>
  
                              <b>2.4.4</b> O prazo para apresentação da documentação comprobatória da condição de universitário, a fim de
                              aproveitar o cumprimento de carência e CPT, será de 30 (trinta) dias a contar da data que completar a
                              maioridade prevista no Item 2.1 letra “b”.<br>
  
                              <b>2.5</b> As inclusões se efetivarão dentro do mês de sua comunicação, sendo observado o prazo máximo de
                              10 (dez) dias úteis contados do recebimento da solicitação para o cadastramento.<br>
  
                              <b>2.6</b> A EMPRESA CONTRATANTE fornecerá a relação nominal dos BENEFICIÁRIOS que deverão ser
                              vinculados ao plano e os enviará em formulários apropriados a serem fornecidos pela ADMINISTRADORA;<br>
  
                              <b>2.6.1</b> Será necessária a comprovação da dependência dos familiares designados em relação aos seus
                              funcionários ou estatutários conforme Cláusulas 2.1 e 2.2.<br>
  
                              <b>2.6.2</b> É obrigatório o vínculo empregatício ou estatutário entre o beneficiário titular e a EMPRESA
                              CONTRATANTE, sendo que tanto a OPERADORA quanto a ADMINISTRADORA se reservam no direito
                              de exigir documentação comprobatória durante o período de vigência do contrato.<br>
  
                              <b>2.7</b> A EMPRESA CONTRATANTE informará, mensalmente, à ADMINISTRADORA, em formulários próprios,
                              o efetivo mensal do corpo funcional e seus dependentes;<br>
  
                              <b>2.7.1</b> O efetivo mensal será calculado com base no número de BENEFICIÁRIOS cadastrados pela EMPRESA
                              CONTRATANTE;<br>
  
                              <b>2.8</b> As adesões dos BENEFICIÁRIOS titulares e dependentes serão automáticas na data da contratação do
                              plano ou para as adesões posteriores, no ato da vinculação do beneficiário à EMPRESA CONTRATANTE;<br>
  
                              <b>2.8</b> As adesões dos BENEFICIÁRIOS titulares e dependentes serão automáticas na data da contratação do
                              plano ou para as adesões posteriores, no ato da vinculação do beneficiário à EMPRESA CONTRATANTE;<br>
  
                              <b>3</b> A EMPRESA CONTRATANTE declara ser a única responsável pelos documentos e informações fornecidas
                              por ela e de seu corpo de beneficiário(s) e dependente(s) e sobre toda e qualquer circunstância que possa
                              influenciar na aceitação deste Contrato e na manutenção ou no valor mensal do mesmo, sabendo que
                              omissões ou dados errôneos acarretarão a perda de todos os direitos, bem como o(s) do(s) beneficiário(s)
                              dependente(s).<br>
  
                              <br><b>3.1</b> A EMPRESA CONTRATANTE deverá apresentar a documentação descrita abaixo:<br><br>
                              • Contrato Social e/ou última Alteração Contratual;<br>
                              • cópia do comprovante de endereço da EMPRESA CONTRATANTE (água, luz ou telefone);<br>
                              • comprovante de vínculo de todos os BENEFICIÁRIOS (GFIP e/ou SEFIP);<br>
                              • CTPS, Ficha de Registro ou Contrato de Trabalho/Estágio para recém contratados e que ainda não
                              constam na GFIP e SEFIP;
                              ';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais2);
  
      $condicoes_gerais3 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                            • cópia da Identificação Civil do(s) beneficiário(s) – RG;<br>
                            • cópia do CPF (inclusive para dependentes maiores de 18 anos) do(s) beneficiário(s);<br>
                            • cópia da Certidão de Casamento ou Declaração de União Estável do(s) beneficiário(s);<br>
                            • cópia da Certidão de Nascimento ou Certidão Tutelar do(s) beneficiário(s).<br><br>
  
                            <b>4</b> Por ser tratar de um Contrato Coletivo Empresarial, a EMPRESA CONTRATANTE e seus BENEFICIÁRIOS
                            com vínculo empregatício e estatutário, além de seus dependentes, está sujeita às condições contratuais
                            aqui expressas, não se submetendo às regras inerentes aos contratos individuais, ficando outorgados à
                            ADMINISTRADORA amplos poderes para representar, assim como seu(s) beneficiário(s) dependente(s),
                            perante a OPERADORA e outros órgãos, em especial a ANS, no cumprimento e/ou nas alterações deste
                            benefício, bem como nos reajustes dos seus valores mensais.<br><br>
  
                            <b>4.1</b> A ADMINISTRADORA, na defesa do interesse de seus BENEFICIÁRIOS, poderá alterar a OPERADORA
                            que atende este Contrato, independente de prévio aviso aos seus consumidores.
                            DA COBERTURA E PROCEDIMENTOS GARANTIDOS<br><br>
  
                            <b>5</b> A prestação de serviços de assistência médica e afins será prestada de acordo com as coberturas e
                            segmentações do “Rol de Procedimentos da ANS” vigente, não cabendo reembolso nos procedimentos
                            não cobertos por este rol.
  
                            <br><br><b>DA VIGÊNCIA, DA VALIDADE E DA RESCISÃO DO CONTRATO</b><br><br>
  
                            <b>6</b> Após a aceitação deste Contrato sua vigência e do(s) seu(s) dependente(s), se houver, estará
                            impreterivelmente condicionada ao pagamento do valor total da mensalidade a ser quitada no primeiro
                            boleto bancário emitido pela ADMINISTRADORA, podendo ser todo dia 5 (cinco) ou 20 (vinte) de cada
                            mês conforme a opção definida no FORMULÁRIO EMPRESA da PROPOSTA DE ADESÃO.<br><br>
  
                            <b>6.1</b> Em caso de não pagamento do primeiro boleto em seu vencimento, o cadastro referente a Proposta
                            de Adesão será cancelado em até 30 (trinta) dias, não isentando a cobrança deste. Caso a EMPRESA
                            CONTRATANTE tenha interesse em efetuar a aquisição de um novo plano deverá procurar um corretor
                            e será necessário o preenchimento de uma nova Proposta de Adesão juntamente com o envio dos
                            documentos necessários.<br><br>
  
                            <b>6.2</b> A cobrança da taxa de corretagem não representa o pagamento da primeira mensalidade do plano de
                            assistência à saúde, e o início da vigência do plano e do Contrato somente se dará após o pagamento do
                            primeiro boleto de cobrança emitido pela ADMINISTRADORA na data escolhida na Proposta de Adesão.<br><br>
  
                            <b>7</b> A ADMINISTRADORA não se responsabilizará por quaisquer atos, promessas ou compromissos
                            efetuados por corretores que estejam em desacordo com as cláusulas expressas neste Contrato.<br><br>
  
                            <b>8</b> A EMPRESA CONTRATANTE poderá declinar este Contrato, sem nenhum ônus, desde que tal decisão
                            seja comunicada por escrito à ADMINISTRADORA no prazo máximo de 7 (sete) dias contados a partir da
                            data da assinatura deste instrumento, autorizando a cobrança da Taxa de Cadastramento e Implantação e
                            do valor mensal do benefício, caso esse prazo não seja observado. <br><br>
  
                            <b>9</b> O presente Contrato é passível de devolução caso não haja contato telefônico de pós-venda e précadastro
                            com o responsável da EMPRESA CONTRATANTE e os titulares responsáveis para confirmação
                            de dados. <br><br>
  
                            <b>10</b> Deve ser observada a importância de que todas as informações sejam verdadeiras para que não ocorram
                            prejuízos ou danos aos demais participantes e, de acordo com o Artigo 766 do Código Civil Brasileiro,';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais3);
  
      $condicoes_gerais4 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              se omitidas circunstâncias que possam influenciar na aceitação ou na mensalidade, poderá ensejar na
                              perda de todo e qualquer direito inerente à mesma. As informações prestadas na Declaração de Saúde
                              deverão ser absolutamente verdadeiras e completas, ficando a ADMINISTRADORA autorizada a solicitar,
                              a qualquer momento, documentação comprobatória das informações fornecidas. Em caso de fraude o
                              beneficiário será cancelado de imediato de acordo com o previsto no Art. 13, Inciso II da Lei nº 9.656/98.
                              <br><br><b>11</b> O Contrato Coletivo Empresarial será renovado no mês de JUNHO, por igual período, desde que não
                              ocorra denúncia por escrito por parte da OPERADORA ou da ADMINISTRADORA do Contrato. Em caso
                              de rescisão desse Contrato coletivo, a ADMINISTRADORA fará a comunicação desse fato.<br><br>
  
                              <b>DAS CARÊNCIAS</b>
                              <br><br><b>12</b> Os prazos de carência são períodos nos quais o(s) beneficiário(s) poderá(rão) realizar determinadas
                              coberturas desde que esteja em dia com o pagamento. Haverá prazos de carências para utilização do(s)
                              benefício(s) contados a partir da data de vigência e cobertura.
  
                              <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; text-align: left; margin-top: 20px;" class="borda">
                                <tr>
                                  <td width="50%"> <center>PROCEDIMENTOS</center> </td>
                                  <td width="50%"> <center>PERÍODO</center> </td>
                                </tr>
  
                                <tr>
                                  <td width="50%"> Acidentes pessoais, Procedimentos de urgência e emergência </td>
                                  <td width="50%"> <center>24 horas após a vigência </td>
                                </tr>
  
                                <tr>
                                  <td width="50%"> Consulta e exames simples </td>
                                  <td width="50%"> <center>30 dias após a vigência </td>
                                </tr>
  
                                <tr>
                                  <td width="50%"> Fisioterapia </td>
                                  <td width="50%"> <center>180 dias após a vigência </td>
                                </tr>
  
                                <tr>
                                  <td width="50%"> Exames/procedimentos especiais de diagnósticos e
                                    terapia; cirurgias ambulatoriais e internações clínicas ou
                                    cirúrgicas; exames/procedimentos que exijam internação;
                                    exames/procedimentos que não estejam relacionados
                                    anteriormente e não estejam excluídos no Rol de
                                    procedimentos editados pela ANS a partir da RN 167/2008
                                    e suas atualizações; radiologia com intervenção genética,
                                    mapeamento cerebral, órtese, prótese, radioterapia/
                                    oncologia, e terapia ocupacional
                                  </td>
                                  <td width="50%"> <center>180 dias após a vigência </td>
                                </tr>
  
                                <tr>
                                  <td width="50%"> Procedimento Obestétricos e Parto </td>
                                  <td width="50%"> <center>300 dias após a vigência </td>
                                </tr>
  
                                <tr>
                                  <td width="50%"> Doenças ou Lesões Preexistentes </td>
                                  <td width="50%"> <center>720 dias após a vigência </td>
                                </tr>
                              </table>
  
                              <br><b>DAS DOENÇAS E LESÕES PREEXISTENTES</b><br><br>
  
                              <b>13</b> Doença ou lesão preexistente é aquela em que o beneficiário saiba ser ou ter sido portador no momento
                              da contratação do plano de saúde.
  
                              <br><br><b>13.1</b> Cobertura Parcial Temporária (CPT) é a suspensão, por um período ininterrupto de até 24 meses,
                              a partir da data da contratação ou adesão ao plano privado de assistência à saúde, da cobertura de
                              Procedimentos de Alta Complexidade (PAC), leitos de alta tecnologia e procedimentos cirúrgicos, desde
                              que relacionados exclusivamente às doenças e lesões preexistentes.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais4);
  
      $condicoes_gerais5 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              <b>13.2</b> O beneficiário deverá preencher, no momento da contratação, a Declaração de Saúde conforme
                              disposto na Resolução Normativa nº 162 de 17 de outubro de 2007 da ANS.<br><br>
  
                              <b>14</b> Sendo constatada a existência de lesão ou doença preexistente que possa gerar necessidade de eventos
                              cirúrgicos, uso de leitos de alta tecnologia ou procedimentos de alta complexidade, o(s) beneficiário(s)
                              deverá(rão) cumprir a Cobertura Parcial Temporária (CPT) cujo prazo será de no máximo 24 (vinte e
                              quatro) meses a contar da vigência do beneficiário. Findado o prazo, a cobertura do plano passará a ser
                              integral, não cabendo qualquer tipo de agravo por doença ou lesão preexistente.<br><br>
  
                              <b>15</b> Será considerado como comportamento fraudulento a omissão de doença ou lesão preexistente de
                              conhecimento prévio do beneficiário.<br><br>
  
                              <b>15.1</b> Alegada a existência de doença ou lesão preexistente não declarada pelo beneficiário no preenchimento
                              da Declaração de Saúde, o beneficiário será imediatamente comunicado pela OPERADORA. Caso o
                              beneficiário não concorde com a alegação, a OPERADORA encaminhara a documentação pertinente à
                              Agência Nacional de Saúde Suplementar/ANS, e esta abrirá processo administrativo para investigação.<br><br>
  
                              15.2 Cumpre esclarecer que durante o período em que a ANS estiver analisando o referido processo
                              investigatório, a OPERADORA poderá realizar o procedimento pretendido normalmente. Entretanto, se
                              ao término do processo investigatório for constatada a omissão do beneficiário em relação as doenças ou
                              lesões preexistentes, este deverá ressarcir, integralmente, todas as despesas decorrentes do procedimento
                              realizado à OPERADORA.<br><br>
  
                              <b>DA URGÊNCIA E DA EMERGÊNCIA</b>
  
                              <br><br><b>16</b> Atendimento de Urgência e Emergência
                              Para efeitos desta cobertura, entende-se como atendimento de emergência aquele que implica no risco
                              imediato de vida ou de lesões irreparáveis para o paciente, caracterizado em declaração do médico
                              assistente. Como atendimento de urgência entende-se aquele resultante de acidente pessoal ou de
                              complicações no período gestacional.<br><br>
  
                              <b>16.1</b> Para os casos de urgência e emergência a OPERADORA garantirá assistência médica no sentido da
                              preservação da vida, órgãos e funções.<br><br>
  
                              <b>16.2</b> Quando o atendimento de urgência e emergência for efetuado no decorrer dos períodos de carência
                              será garantida cobertura e medicação limitadas até as primeiras 12 (doze) horas. Quando necessária, para
                              a continuidade de internação do atendimento de urgência e emergência, após o prazo das 12 (doze)
                              horas, a cobertura cessará, sendo do beneficiário a responsabilidade financeira, não cabendo ônus à
                              OPERADORA. A remoção do paciente será realizada pela OPERADORA para uma unidade do Serviço
                              Único de Saúde (SUS) que possua os recursos necessários para garantir a continuidade do atendimento.<br><br>
  
                              <b>DA EXECUÇÃO DOS SERVIÇOS</b>
  
                              <br><br><b>17</b> Área de Abrangência e Locais de Atendimento
                              O referido Plano possui Abrangência Geográfica de grupo de municípios sendo estes: Canoas, Gravataí,
                              Porto Alegre e Viamão.
                              <br><br><b>18</b> É obrigatória a apresentação da Cédula de Identificação com foto para usufruir dos atendimentos e
                              recursos deste Contrato.
                              <br><br><b>19</b> A rede credenciada de prestadores coberta pelo plano de saúde deverá ser consultada através do';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais5);
  
      $condicoes_gerais6 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              site da OPERADORA (https://www.vertesaude.com.br/). Fica estabelecido que o(s) beneficiário(s) e seus
                              dependentes utilizarão o plano na rede credenciada mediante apresentação da numeração de matrícula
                              do plano e/ou a carteira provisória expedida pela ADMINISTRADORA através dos canais de atendimento
                              e/ou o cartão definitivo (não obrigatório).<br>
  
                              <b>20</b> A OPERADORA se reserva, outrossim, o direito de modificar, extinguir ou realizar novos convênios de
                              credenciamento de profissionais, clínicas e pronto socorros, mantendo sempre o seu alto padrão técnico.<br><br>
  
                              <b>21</b> Na hipótese da substituição de estabelecimento hospitalar por outro equivalente, a OPERADORA
                              comunicará a Agência Nacional de Saúde Suplementar/ANS no prazo de 30 (trinta) dias de antecedência,
                              garantindo assim a continuidade da internação.
  
                              <br><br><b>DA FORMAÇÃO DO PREÇO E DA MENSALIDADE</b><br><br>
  
                              <b>22</b> Após o fechamento do efetivo mensal, a ADMINISTRADORA enviará fatura correspondente aos
                              atendimentos/mensalidades realizados no mês, sendo de responsabilidade da EMPRESA CONTRATANTE
                              o pagamento da mesma na totalidade de BENEFICIÁRIOS inscritos, ressalvadas as hipóteses previstas
                              nos Art. 30 e 31 da Lei nº 9.656/98.<br><br>
  
                              <b>23</b> A EMPRESA CONTRATANTE efetuará a quitação mensal, na data de vencimento escolhida no ato
                              da assinatura deste Contrato, pelo número de BENEFICIÁRIOS vinculados a empresa, sendo os valores
                              determinados de acordo com a faixa etária e produto escolhido por cada usuário.<br><br>
  
                              <b>23.1</b> Será responsabilidade da EMPRESA CONTRATANTE avisar à ADMINISTRADORA o não recebimento
                              do boleto até 2 (dois) dias úteis antes do seu vencimento. Após a data de vencimento da fatura incidirão
                              multa de 2% sobre o valor do débito em atraso e juros de mora de 1,0% ao mês, estando a ADMINISTRADORA
                              autorizada a realizar cobrança, caso haja pendência financeira, através de SMS, cartas, e-mails ou qualquer
                              outro meio de comunicação legal.<br><br>
  
                              <b>23.2</b> Será cobrada a Taxa de Reemissão de boleto caso a solicitação da segunda via seja feita após a data
                              do vencimento original da mensalidade, e seu valor será de R$ 3,00 (três reais), podendo ser reajustado
                              conforme a base tarifária do banco emissor. Todos os comunicados que forem entregues com a fatura
                              serão considerados notificações extrajudiciais.<br><br>
  
                              <b>23.3</b> Caso não ocorra o pagamento em seu vencimento, a ADMINISTRADORA se reserva ao direito
                              de proceder a inclusão do número do CNPJ da EMPRESA CONTRATANTE nos cadastros dos órgãos
                              restritivos de crédito, caso haja atrasos superiores a 30 (trinta) dias, bem como de seu representante
                              legal, o qual obriga-se a observar e cumprir os prazos e condições de pagamento estipulados neste
                              Contrato, como também respeitar as normas e regulamentos do benefício, respondendo civil e
                              criminalmente por quaisquer danos morais e materiais eventualmente causados por si e pela utilização
                              indevida do plano de saúde.<br><br>
  
                              <b>24</b> Não haverá distinção quanto ao valor da prestação entre os BENEFICIÁRIOS que vierem a ser incluídos
                              no Contrato e aqueles a este já vinculado.<br><br>
  
                              <b>25</b> Todos os pagamentos mensais serão efetuados pela EMPRESA CONTRATANTE à ADMINISTRADORA
                              através de boleto bancário nos prazos de cobrança e na forma estabelecida nos documentos emitidos
                              pela ADMINISTRADORA, não realizando a mesma, em hipótese alguma, cobrança domiciliar. Para
                              qualquer outro meio de pagamento, o mesmo somente poderá ser efetuado com prévia autorização da
                              ADMINISTRADORA.<br><br>
  
                              <b>26</b> A EMPRESA CONTRATANTE reconhece que os valores estabelecidos neste Contrato são líquidos e
                              certos, legitimando a emissão de faturamento mensal em conformidade com esta Cláusula e procedimento
                              executivo nos casos de inadimplência com a inclusão, então, dos juros legais e das despesas processuais,
                              advocatícias e demais cominações legais.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais6);
  
      $condicoes_gerais7 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              <b>DO FATOR MODERADOR</b>
  
                              <br><b>27</b> O referido plano contratado possui Franquia, sendo este o valor financeiro a ser pago pelo beneficiário
                              diretamente ao prestador da rede credenciada, rede própria ou referenciada no ato da utilização do serviço.<br><br>
  
                              <b>27.1</b> Segue tabela de cobrança de Franquia:
                              <table border="1" cellspacing="0" style="width: 100%; text-align: center; font-size: 11px; margin-top: 5px;">
                                <tr>
                                 <th>Valores de Franquia</th>
                                 <td>VERTE SAÚDE</td>
                                 <td>Rede <br>Credenciada</td>
                                </tr>
  
                                <tr>
                                 <td>Consultas</td>
                                 <td>Isento</td>
                                 <td>R$ 18,00</td>
                                </tr>
  
                                <tr>
                                 <td>Fisioterapia</td>
                                 <td colspan="2">R$ 6,00</td>
                                </tr>
  
                                <tr>
                                 <td>Terapia Ocupacional</td>
                                 <td colspan="2">R$ 10,00</td>
                                </tr>
  
                                <tr>
                                 <td>Fonoaudiologia</td>
                                 <td colspan="2">R$ 10,00</td>
                                </tr>
  
                                <tr>
                                 <td>Psicologia</td>
                                 <td colspan="2">R$ 12,00</td>
                                </tr>
  
                                <tr>
                                 <td>Nutrição</td>
                                 <td colspan="2">R$ 10,00</td>
                                </tr>
  
                                <tr>
                                 <td>Internação Psiquiátrica</td>
                                 <td colspan="2">R$ 70,00</td>
                                </tr>
  
                                <tr>
                                 <td>Petscan</td>
                                 <td colspan="2">R$ 500,00</td>
                                </tr>
  
                                <tr>
                                 <td>Acunpuntura</td>
                                 <td colspan="2">R$ 9,00</td>
                                </tr>
  
                                <tr>
                                 <td>Mamografia</td>
                                 <td colspan="2">R$ 14,00</td>
                                </tr>
  
                                <tr>
                                 <td>Tomografia</td>
                                 <td colspan="2">R$ 40,00</td>
                                </tr>
  
                                <tr>
                                 <td>Ressonância</td>
                                 <td colspan="2">R$ 80,00</td>
                                </tr>
  
                                <tr>
                                 <td>Câmara Hiperbárica</td>
                                 <td colspan="2">R$ 80,00</td>
                                </tr>
                              </table>
  
                              <br>
  
                              <b>27.2</b> Os valores referentes a tabela de franquia poderão sofrer reajuste conforme o aniversário de contrato.
  
                              <br><br><b>DO REAJUSTE</b><br>
  
                              <b>28</b> A atualização dos valores dos custos mensais será efetuada anualmente, no mês de aniversário do
                              Contrato entre a ADMINISTRADORA e a OPERADORA, e ocorrerá no mês de jUNHO de cada ano,
                              independente da data da adesão ao Contrato, pela variação do IGPM, que será apurado no período de 12
                              meses consecutivos, sendo aplicada a todos os usuários ativos no Contrato, independentemente da idade.<br><br>
  
                              <b>28.1</b> O percentual reajustado será informado à ANS até 30 (trinta) dias após a data da aplicação.<br><br>
  
                              <b>29</b> Independentemente da data de inclusão dos usuários, os valores de suas contraprestações sofrerão o
                              primeiro reajuste integral na data de aniversário (vigência) do Contrato ou quando em razão de mudança
                              de faixa etária, migração e adaptação do Contrato à Lei nº 9.656/98;<br><br>
  
                              <b>30</b> Na hipótese de se constatar a necessidade de aplicação do reajuste por sinistralidade, este será negociado
                              de comum acordo entre a ADMINISTRADORA e a OPERADORA, sendo que o nível de sinistralidade da
                              carteira terá por base a proporção entre as despesas assistenciais e as receitas diretas do plano, apuradas
                              no período de 12 (doze) meses consecutivos, anteriores à data base de aniversário considerada como o
                              mês de assinatura do contrato entre a EMPRESA CONTRATANTE e a OPERADORA.<br><br>
  
                              <b>30.1</b> Fica estabelecido para este Contrato o ponto de equilíbrio a ser considerado para eventual sinistralidade
                              em 75% (setenta e cinco por cento).<br><br>
  
                              <b>31</b> Nos casos de aplicação de reajuste por sinistralidade, o mesmo será procedido de forma complementar
                              ao especificado no Item 30 e nas mesmas datas.<br><br>
  
                              <b>32</b> Será calculado valor único de percentual de reajuste para o agrupamento ao qual está agregado o Contrato.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais7);
  
      $condicoes_gerais8 .= '<br><br><br><div style="text-align: justify; font-size: 13px;">
                            <br><b>DAS FAIXAS ETÁRIAS</b><br><br>
  
                            <b>33</b> Variação do preço em razão da faixa etária<br>
                            Havendo alteração de faixa etária de beneficiário inscrito no presente Contrato, a contraprestação
                            pecuniária será reajustada no mês subsequente ao da ocorrência, de acordo com os valores da tabela
                            abaixo, que se acrescentarão sobre o valor da última contraprestação pecuniária, observadas a seguintes
                            condições, conforme determina o Art. 3º, Incisos I e II da RN nº 63/03.
  
                            <table border="1" cellspacing="0" style="width: 100%; text-align: center; margin-left: 230px; font-size: 11px; margin-top: 25px; width: 50%;">
                              <tr>
                               <th> FAIXA ETÁRIA </th>
                               <th> VARIAÇÃO DE <br>FAIXA ETÁRIA </th>
                              </tr>
  
                              <tr>
                               <td> 00 a 18 </td>
                               <td> 0% </td>
  
                              </tr>
  
                              <tr>
                               <td> 19 a 23 </td>
                               <td> '.$var1_result_global.'% </td>
  
                              </tr>
  
                              <tr>
                               <td> 24 à 28 </td>
                               <td> '.$var2_result_global.'% </td>
  
                              </tr>
  
                              <tr>
                               <td> 29 à 33 </td>
                               <td> '.$var3_result_global.'% </td>
  
                              </tr>
  
                              <tr>
                               <td> 34 à 38 </td>
                               <td> '.$var4_result_global.'% </td>
  
                              </tr>
  
                              <tr>
                               <td> 39 à 43 </td>
                               <td> '.$var5_result_global.'% </td>
  
                              </tr>
  
                              <tr>
                               <td> 44 à 48 </td>
                               <td> '.$var6_result_global.'% </td>
  
  
                              <tr>
                               <td> 49 à 53 </td>
                               <td> '.$var7_result_global.'% </td>
  
                              </tr>
  
                              <tr>
                               <td> 54 à 58 </td>
                               <td> '.$var8_result_global.'% </td>
  
                              </tr>
  
                              <tr>
                               <td> > 59 </td>
                               <td> '.$var9_result_global.'% </td>
  
                              </tr>
  
                              <tr>
                               <td> <b>Total de benefíciários</b> </td>
                               <td> '.$var10_result_global.'% </td>
                              </tr>
                            </table><br>
  
                            <b>DAS REGRAS PARA INSTRUMENTOS JURÍDICOS DE PLANOS COLETIVOS POR ADESÃO</b>
                            <br><br><b>Termo de Permanência</b><br><br>
  
                            <b>34</b> O beneficiário titular que for demitido ou exonerado sem justa causa, decorrente da sua relação de
                            trabalho com a EMPRESA CONTRATANTE, terá o direito de formalizar Termo de Permanência conforme
                            as regras dos Art. 30 e 31 da Lei nº 9.656/98 e RN nº 279 da ANS.<br><br>
  
                            <b>35</b> A OPERADORA assegura ao beneficiário titular e seus dependentes vinculados já inscritos o direito de
                            manter sua condição de beneficiário no plano de saúde, nas mesmas condições de cobertura assistencial
                            de que gozava quando da vigência do Contrato de Trabalho quando este for desligado ou exonerar-se da
                            empresa, sem justa causa, através da formalização do Termo de Permanência.<br><br>
                            <b>36</b> Pagamento
                            O beneficiário que realizar o Termo de Permanência assumirá o pagamento integral diretamente à
                            OPERADORA que lhe fornecerá as devidas instruções na forma do Art 30 da Lei nº 9.656/98 c/c RN nº
                            179/2011 da ANS.<br><br>
  
                            <b>37</b> Requisitos para realização do Termo de Permanência
                            O termo de permanência só será permitido quando cumprido os seguintes requisitos legais:<br>
                            a) o ex-empregado formalizar o pedido de realização do Termo de Permanência no prazo máximo
                            de 30 (trinta) dias a contar da assinatura do Formulário de Exclusão formalizado junto à EMPRESA
                            CONTRATANTE, que será entregue no ato do comunicado de rescisão do Contrato de Trabalho.<br>
                            b) contribuição do beneficiário titular pelo plano de saúde, através de desconto em Folha de Pagamento, no
                            qual comprove que este contribuiu total ou parcialmente pelo plano em decorrência do vínculo empregatício,
                            com exceção dos valores realizados à contribuição de dependentes, agregados e coparticipação.<br>
                            c) assuma o pagamento integral do plano, conforme a tabela de valores e suas atualizações, estabelecida
                            no contrato principal firmado com a EMPRESA CONTRATANTE.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais8);
  
      $condicoes_gerais9 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              <b>38</b> Período de Manutenção
                              O período de manutenção do beneficiário no Termo de Permanência será de 1/3 (um terço) do tempo de
                              permanência no seu plano anterior, com um mínimo assegurado de 06 (seis) meses e um máximo de 24
                              (vinte e quatro) meses.<br><br>
  
                              <b>39</b> Extensão ao Grupo Familiar
                              O Termo de Permanência poderá ser celebrado individualmente ou estendido a todo grupo familiar do
                              beneficiário, quando inscritos durante a vigência do Contrato de Trabalho.<br><br>
  
                              <b>39.1</b> É permitida a inclusão junto ao Termo de Permanência somente para novo conjuge e filhos do exempregado
                              demitido, exonerado ou aposentado.<br><br>
  
                              <b>40</b> Condições de perda do Termo de Permanência<br>
                              a) quando do término da contagem de prazo de permanência estipulado no momento da assinatura do
                              termo;<br>
                              b) pela admissão do beneficiário em novo emprego que lhe possibilite ingresso em novo plano; ou<br>
                              c) cancelamento do plano coletivo empresarial ao qual o beneficiário demitido encontrava-se vinculado.<br><br>
  
                              <b>41</b> Morte do Titular<br>
                              Em caso de morte do titular, o direito de permanência é assegurado aos dependentes cobertos pelo
                              plano privado coletivo de assistência à saúde, desde que assumam as mensalidades correspondentes aos
                              dependentes que optarem por permanecerem com o plano.
  
                              <br><br><b>42</b> Aposentados<br>
                              Ao aposentado que contribuiu comprovadamente para o plano contratado decorrente de vínculo
                              empregatício, pelo prazo mínimo de 10 (dez) anos, a OPERADORA assegura ao beneficiário titular e seus
                              dependentes vinculados, o direito de manutenção como beneficiário no plano de saúde nas mesmas
                              condições de cobertura assistencial de que gozava quando da vigência do Contrato de Trabalho, desde
                              que assuma junto à OPERADORA o pagamento integral das mensalidades, na forma do Art. 31 da Lei nº
                              9.656/98.<br><br>
  
                              <b>42.1</b> Ao aposentado que contribuiu comprovadamente para o plano, por período inferior a 10 (dez) anos,
                              é assegurado o direito de realizar o Termo de Permanência à razão de 1 (um) ano para cada ano de
                              contribuição, desde que assuma o pagamento integral do plano.<br><br>
  
                              <b>42.2</b> O aposentado deve optar pela manutenção do benefício no prazo máximo de 30 dias a contar da
                              assinatura do Formulário de Exclusão formalizado junto à EMPRESA CONTRATANTE que será entregue
                              no ato do comunicado de rescisão contratual;<br><br>
  
                              <b>43</b> Aposentado que permanecer trabalhando:
                              O aposentado que continuar trabalhando na mesma empresa e venha a se desligar desta, é garantido o
                              direito de manter sua condição de beneficiário através do Termo de Permanência, devendo este optar pela
                              manutenção no prazo máximo de 30 (trinta) dias a contar da data de seu desligamento junto à EMPRESA
                              CONTRATANTE e da assinatura do Formulário de Exclusão;<br><br>
  
                              <b>43.1</b> O Termo de Permanência também poderá ser estendido aos dependentes já inscritos do empregado
                              aposentado que continuou trabalhando na mesma empresa e que veio a falecer antes de se desligar,
                              cabendo a estes optar pela manutenção no prazo máximo de 30 (trinta) dias a contar do óbito e da
                              assinatura do Formulário de Exclusão.<br><br>
  
                              <b>44</b> Cancelamento do Plano Empresarial:
                              ';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais9);
  
      $condicoes_gerais10 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              Em caso de cancelamento dos planos coletivos empresariais terão os BENEFICIÁRIOS a garantia de
                              continuar com o plano de saúde na modalidade coletivo por adesão ou empresarial, sem a necessidade do
                              cumprimento de novos prazos de carências, conforme Súmula Normativa 21 da ANS, desde que comunique
                              a OPERADORA no prazo máximo de 30 (trinta) após o seu término.<br><br>
  
                              <b>45</b> Formas de contribuições permitidas:<br>
                              Nos planos custeados integralmente pela EMPRESA CONTRATANTE, quando o titular não participar
                              financeiramente do plano durante o período que mantiver o vínculo empregatício, este não terá direito
                              ao Termo de Permanência. Não é considerada contribuição a coparticipação do beneficiário, única e
                              exclusivamente em procedimentos, como fator de moderação na utilização dos serviços de assistência
                              médica e/ou hospitalar.<br><br>
  
                              <b>46</b> O direito assegurado ao beneficiário titular, demitido ou aposentado, não exclui vantagens obtidas
                              pelos empregados decorrentes de negociações coletivas de trabalho.<br><br>
  
                              <b>DAS CONDIÇÕES DE PERDA DA QUALIDADE DE BENEFICIÁRIO</b><br><br>
  
                              <b>47</b> Compete à EMPRESA CONTRATANTE, na vigência deste Contrato, comunicar imediatamente as
                              ocorrências de demissões realizadas no período, bem como o recolhimento e a devolução das respectivas
                              Cédulas de Identificação, sendo que caberá somente à EMPRESA CONTRATANTE solicitar a exclusão dos
                              BENEFICIÁRIOS.<br><br>
  
                              <b>47.1</b> As solicitações de exclusões devem conter a assinatura e o carimbo do representante legal da
                              EMPRESA CONTRATANTE;<br><br>
  
                              <b>47.2</b> As exclusões serão realizadas após 05 (cinco) dias do recebimento pela OPERADORA;<br><br>
  
                              <b>47.3</b> A EMPRESA CONTRATANTE será responsável pelos atendimentos prestados aos usuários demitidos
                              ou excluídos cujos nomes não tenham sido comunicados a OPERADORA, em tempo hábil, cabendo-lhe
                              indenizar os seus custos;<br><br>
  
                              <b>47.4</b> Na falta de comunicação em tempo oportuno, da inclusão ou da exclusão de BENEFICIÁRIOS, a
                              fatura se baseará nos dados disponíveis, sendo os eventuais acertos realizados na fatura subsequente.<br><br>
  
                              <b>48</b> Cancelamento a pedido – RN 412
                              O benecifiário que desejar realizar o cancelamento de seu plano de saúde, deverá solicitar a exclusão junto
                              a EMPRESA CONTRATANTE.<br><br>
  
                              <b>48.1</b> A EMPRESA CONTRATANTE deverá enviar a solicitação de cancelamento a ADMINISTRADORA
                              através do e-mail rempresarial@grupocontem.com.br, encaminhando a solicitação por escritopelo
                              responsável pela EMPRESA CONTRATANTE.<br><br>
  
                              <b>48.2</b> Caso o beneficiário solicite a portabilidade de seu plano para outra OPERADORA, ao concluir a
                              portabilidade, o beneficiário deverá solicitar o cancelamento do seu vínculo com a ADMINISTRADORA ou
                              OPERADORA de origem no prazo de 5 (cinco) dias a partir da data do início da vigência do seu vínculo
                              com o plano de destino.<br><br>
  
                              <b>49</b> A OPERADORA só poderá cancelar a assistência à saúde dos BENEFICIÁRIOS, sem a anuência da
                              EMPRESA CONTRATANTE, nos seguintes casos:<br><br>
  
                              a) fraude comprovada mediante notificação formal ao beneficiário;<br>
                              b) perda do vínculo do titular com a EMPRESA ou de dependência, ressalvado o disposto nos Art. 30 e 31
                              da Lei nº 9.656/98; ou';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais10);
  
      $condicoes_gerais11 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              c) agressão verbal ou física aos colaboradores da OPERADORA.<br><br>
                              <b>49.1</b> Para todos esses casos haverá comunicação formal da decisão à EMPRESA CONTRATANTE pela
  
                              OPERADORA.<br><br>
  
                              <b>DA RESCISÃO</b><br>
                              <b>50</b> O presente Contrato poderá ser rescindido nas hipóteses de fraude ou não pagamento da taxa mensal,
                              por período superior a 30 (trinta) dias consecutivo ou não, a cada 12 (doze) meses de vigência do Contrato,
                              cabendo à ADMINISTRADORA notificar a EMPRESA CONTRATANTE.<br>
  
                              <b>51</b> A omissão de informações ou fornecimento de informações incorretas ou inverídicas pela EMPRESA
                              CONTRATANTE para auferir vantagens próprias ou para seus usuários é reconhecida como violação ao
                              Contrato, permitindo à ADMINISTRADORA e à OPERADORA realizar a rescisão do Contrato por fraude.<br><br>
  
                              <b>52</b> Caso não ocorra a quitação da mensalidade em até 05 (cinco) dias a contar da data do vencimento
                              original do boleto bancário, poderá ocorrer a suspensão do(s) beneficiários(as) e a utilização somente
                              será reestabelecida a partir da quitação integral do(s) valor(es) pendente(s) acrescido( s) dos encargos
                              supracitados.<br>
  
                              <b>52.1</b> O restabelecimento do serviço em caso de suspensão caso não ocorra a quitação da mensalidade
                              em até 05 (cinco) dias a contar da data do vencimento original, dependerá da comprovação da baixa
                              bancária.<br><br>
  
                              <b>DAS DISPOSIÇÕES GERAIS</b><br>
  
                              <b>53</b> Somente será possível postular nova adesão pela EMPRESA CONTRATANTE, mediante: (I) aceitação
                              pela ADMINISTRADORA, (II) quitação de eventuais débitos anteriores junto à ADMINISTRADORA,
                              mesmo que seja de Contrato de outra OPERADORA, e (III) cumprimento de novos prazos de carência,
                              independentemente do período anterior em que permaneceu no Contrato Coletivo.<br><br>
  
                              <b>54</b> Autorizo, expressamente, ressalvada as formas regulamentares de notificação, receber através de
                              e-mail, SMS e WhatsApp, notificações de cancelamento por inadimplência ao atingir 30 (trinta) dias em
                              atraso no pagamento, rescisão de contrato, inadimplência, reajuste anual, aviso de cobrança, migração e
                              demais avisos.<br><br>
  
                              <b>55</b> O Kit de Implantação, composto da Carteira do Plano e da Carta de Boas Vindas, será enviado ao
                              endereço de correspondência em até 30 (trinta) dias da data de vigência do Contrato através dos Correios
                              ou por empresa terceirizada. Fica excluído qualquer tipo de reembolso.<br><br>
  
                              <b>56</b> Para solicitar a 2ª via da carteira do plano de saúde será cobrado o valor da Taxa de Reemissão de
                              carteira (R$ 35,00 (trinta e cinco reais) por carteira).<br><br>
  
                              <b>57</b> O foro para dirimir quaisquer questões oriundas do presente contrato será o do Porto Alegre/RS, excluindo
                              qualquer outro.<br><br>
  
                              <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px;">
                              <b>Dados da Empresa Contratante </b></div>
                              <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 0px;">
                                <tr>
                                 <td> <b>CNPJ: </b>'.$dados['cnpj'].' </td>
                                 <td> <b>Razão Social: </b>'.$dados['razao_social'].'</td>
                                </tr>
                              </table>
  
                              <table cellspacing="-1" style="width: 100%; font-size: 12px; margin-top: 30px;">
                                <tr>
                                 <td><center> ________________________________________,____,____,________ </center></td>
                                 <td><center> ______________________________________________________ </center></td>
                                </tr>
  
                                <tr>
                                 <td> <center>Local e data</center> </td>
                                 <td> <center>Assinatura do sócio ou representante legal da empresa</center> </td>
                                </tr>
                              </table>
  
                              <div style="text-align: center; margin-top: 10px; width: 95%;">
                                <img src="https://www.grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/cond_gerais/contato_contem.png">
                              </div>';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais11);
  
    } else if($dados['operadora'] == 'LIFEDAY'){
  
      $condicoes_gerais1 = '<div style="text-align: justify; font-size: 13px;">
                            <br><div style="margin-top: 55px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; font-size: 14px; text-align: left; padding: 5px; background-color: #d3d3d3">
                           <b>Condições Gerais</b></div><br>
                           <b> DAS CONDIÇÕES GERAIS </b>
  
                           <br><br>
                            <b>1.</b> Pelo presente instrumento particular, CONTÉM ADMINISTRADORA DE PLANOS DE SAÚDE LTDA,
                            utilizando como nome fantasia CONTÉM , inscrita no CNPJ/MF sob nº 13.286.268/0001-83 e na ANS sob
                            nº 41832-3, situada na Rua do Carmo, nº 08 - 10º andar, Centro - Rio de Janeiro/RJ - CEP 20011-020,
                            denominada neste ato ESTIPULANTE; e LIFEDAY PLANOS DE SAÚDE LTDA, utilizando como nome
                            fantasia LIFEDAY SAÚDE, inscrita no CNPJ/MF sob nº 90.450.412/0001-16 e na ANS sob nº 30969-9,
                            situada na Av. Soledade, 569 / sala 810 – Torre A, Bairro Petrópolis, Porto Alegre - RS - CEP 90.470-340,
                            denominada neste ato OPERADORA, vêm celebrar o presente Contrato de Adesão na modalidade Coletivo
                            Empresarial, de forma a permitir que os sócios, executivos e empregados da EMPRESA CONTRATANTE,
                            bem como seus respectivos dependentes, possam usufruir da cobertura médico-assistencial prevista
                            neste Contrato, em conformidade com as Resoluções Normativas nº 195 e 196 da Agência Nacional de
                            Saúde Suplementar/ANS.<br><br>
  
                            <b> DAS CONDIÇÕES GERAIS </b><br><br>
  
                            <b>1.1</b> O objeto deste Contrato é pactuar a adesão de Pessoa Jurídica, permitindo a ela, consequentemente,
                            proceder a inclusão das pessoas físicas a ela vinculadas por relação empregatícia ou estatutária para que
                            tenham cobertura à prestação continuada de serviços e/ou cobertura de custos assistenciais na forma de
                            Plano Privado de Assistência à Saúde prevista no Inciso I, do Art 1º da Leiº 9.656/98, visando a assistência
                            médica hospitalar com a cobertura de todas as doenças da “Classificação Estatística Internacional de
                            Doenças e Problemas com a Saúde”, da Organização Mundial de Saúde/OMS, obedecendo o “Rol de
                            Procedimentos e Eventos em Saúde” editado pela Agência Nacional de Saúde Suplementar/ANS, vigente
                            à época do evento.<br><br>
  
                            <b> DOS BENEFICIÁRIOS DO CONTRATO </b><br><br>
  
                            <b>2.</b> Pessoas aptas a utilizar os serviços
                            O Plano de Saúde Coletivo Empresarial é aquele que oferece cobertura da atenção prestada à população
                            delimitada e vinculada à EMPRESA CONTRATANTE, por relação empregatícia ou estatutária.
  
                            <b>2.1</b> Serão considerados BENEFICIÁRIOS da prestação de serviços o titular, pertencente ao corpo funcional
                            da EMPRESA CONTRATANTE, podendo ainda, serem inscritos pelo titular seus dependentes, assim
                            constituídos:<br><br>
  
                            a) o cônjuge ou companheiro(a) havendo união estável na forma da lei, do mesmo sexo ou do sexo oposto;<br>
                            b) os filhos, os netos (naturais e/ou adotivos) até 35 (trinta e cinco) anos de idade incompletos (34 anos,
                            11 meses e 29 dias) ou de qualquer idade se inválidos física ou mentalmente em caráter permanente,
                            mediante comprovação de incapacidade;<br>
                            c) os tutelados(as) e curatelados(as), menor sob guarda com o respectivo termo de tutela e curatela ou
                            guarda nos limites etários definidos nesta cláusula.<br><br>
  
                            <b>2.2</b> Também serão considerados BENEFICIÁRIOS da prestação de serviços o titular, pertencente ao corpo
                            funcional da EMPRESA CONTRATANTE, podendo ainda, serem inscritos pelo titular seus dependentes,
                            assim constituídos:<br><br>
                            a) os sócios da EMPRESA CONTRATANTE<br>
                            b) os Administradores da EMPRESA CONTRATANTE<br>
                            c) os demitidos ou aposentados que tenham sido vinculados anteriormente à pessoa jurídica<br>
                            d) os trabalhadores temporários;<br>
                            e) os estagiários e menores aprendizes.<br>
                            ';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais1);
  
      $condicoes_gerais2 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              <b>2.3</b> A adesão do grupo familiar dependerá da participação do titular no plano de saúde.
  
                              <br><b>2.4</b> Cadastramento de BENEFICIÁRIOS:<br>
                              Para uso dos benefícios previstos neste Contrato é indispensável o cadastramento prévio dos
                              BENEFICIÁRIOS titulares e dependentes junto à ADMINISTRADORA e à OPERADORA.<br>
  
                              <b>2.4.1</b> Haverá cobertura contratual ao recém-nascido, filho natural ou adotivo do beneficiário, ou do seu
                              dependente, durante os primeiros 30 (trinta) dias após o parto ou adoção, independente do cadastramento,
                              sendo condicionadas as carências já cumpridas pelo beneficiário titular, sendo vedada qualquer alegação
                              de DLP ou aplicação de CPT ou Agravo;<br>
  
                              <b>2.4.2</b> No caso de inscrição do recém-nascido (filho natural ou adotivo) do beneficiário, dentro do prazo
                              máximo de 30 (trinta) dias do nascimento/adoção, este ingressará no plano com as mesmas carências já
                              cumpridas até a data da inscrição pelo titular;<br>
  
                              <b>2.4.3</b> No caso de inscrição de filho adotivo até 12 anos de idade haverá aproveitamento das carências já
                              cumpridas pelo beneficiário adotante;<br>
  
                              <b>2.4.4</b> O prazo para apresentação da documentação comprobatória da condição de universitário, a fim de
                              aproveitar o cumprimento de carência e CPT, será de 30 (trinta) dias a contar da data que completar a
                              maioridade prevista no Item 2.1 letra “b”.<br><br>
  
                              <b>2.5</b> As inclusões se efetivarão dentro do mês de sua comunicação, sendo observado o prazo máximo de
                              10 (dez) dias úteis contados do recebimento da solicitação para o cadastramento.
  
                              <b>2.6</b> A EMPRESA CONTRATANTE fornecerá a relação nominal dos BENEFICIÁRIOS que deverão ser
                              vinculados ao plano e os enviará em formulários apropriados a serem fornecidos pela ADMINISTRADORA;<br>
  
                              <b>2.6.1</b> Será necessária a comprovação da dependência dos familiares designados em relação aos seus
                              funcionários ou estatutários conforme Cláusulas 2.1 e 2.2.<br>
  
                              <b>2.6.2</b> É obrigatório o vínculo empregatício ou estatutário entre o beneficiário titular e a EMPRESA
                              CONTRATANTE, sendo que tanto a OPERADORA quanto a ADMINISTRADORA se reservam no direito
                              de exigir documentação comprobatória durante o período de vigência do contrato.<br>
  
                              <b>2.7</b> A EMPRESA CONTRATANTE informará, mensalmente, à ADMINISTRADORA, em formulários próprios,
                              o efetivo mensal do corpo funcional e seus dependentes;<br>
  
                              <b>2.7.1</b> O efetivo mensal será calculado com base no número de BENEFICIÁRIOS cadastrados pela EMPRESA
                              CONTRATANTE;<br>
  
                              <b>2.8</b> As adesões dos BENEFICIÁRIOS titulares e dependentes serão automáticas na data da contratação do
                              plano ou para as adesões posteriores, no ato da vinculação do beneficiário à EMPRESA CONTRATANTE;<br><br>
  
                              <b>3</b> A EMPRESA CONTRATANTE declara ser a única responsável pelos documentos e informações fornecidas
                              por ela e de seu corpo de beneficiário(s) e dependente(s) e sobre toda e qualquer circunstância que possa
                              influenciar na aceitação deste Contrato e na manutenção ou no valor mensal do mesmo, sabendo que
                              omissões ou dados errôneos acarretarão a perda de todos os direitos, bem como o(s) do(s) beneficiário(s)
                              dependente(s).<br><br>
  
                              <b>3.1</b> A EMPRESA CONTRATANTE deverá apresentar a documentação descrita abaixo:<br>
                              • Contrato Social e/ou última Alteração Contratual;<br>
                              • cópia do comprovante de endereço da EMPRESA CONTRATANTE (água, luz ou telefone);<br>
                              • comprovante de vínculo de todos os BENEFICIÁRIOS (GFIP e/ou SEFIP);<br>
                              • CTPS, Ficha de Registro ou Contrato de Trabalho/Estágio para recém contratados e que ainda não
                              constam na GFIP e SEFIP;
  
                            ';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais2);
  
      $condicoes_gerais3 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                            • cópia da Identificação Civil do(s) beneficiário(s) – RG;<br>
                            • cópia do CPF (inclusive para dependentes maiores de 18 anos) do(s) beneficiário(s);<br>
                            • cópia da Certidão de Casamento ou Declaração de União Estável do(s) beneficiário(s);<br>
                            • cópia da Certidão de Nascimento ou Certidão Tutelar do(s) beneficiário(s).<br><br>
  
                            <b>4</b> Por ser tratar de um Contrato Coletivo Empresarial, a EMPRESA CONTRATANTE e seus BENEFICIÁRIOS
                            com vínculo empregatício e estatutário, além de seus dependentes, está sujeita às condições contratuais
                            aqui expressas, não se submetendo às regras inerentes aos contratos individuais, ficando outorgados à
                            ADMINISTRADORA amplos poderes para representar, assim como seu(s) beneficiário(s) dependente(s),
                            perante a OPERADORA e outros órgãos, em especial a ANS, no cumprimento e/ou nas alterações deste
                            benefício, bem como nos reajustes dos seus valores mensais.<br><br>
  
                            <b>4.1</b> A ADMINISTRADORA, na defesa do interesse de seus BENEFICIÁRIOS, poderá alterar a OPERADORA
                            que atende este Contrato, independente de prévio aviso aos seus consumidores.
                            DA COBERTURA E PROCEDIMENTOS GARANTIDOS<br><br>
  
                            <b>5</b> A prestação de serviços de assistência médica e afins será prestada de acordo com as coberturas e
                            segmentações do “Rol de Procedimentos da ANS” vigente, não cabendo reembolso nos procedimentos
                            não cobertos por este rol.
  
                            <br><br><b>DA VIGÊNCIA, DA VALIDADE E DA RESCISÃO DO CONTRATO</b><br><br>
  
                            <b>6</b> Após a aceitação deste Contrato sua vigência e do(s) seu(s) dependente(s), se houver, estará
                            impreterivelmente condicionada ao pagamento do valor total da mensalidade a ser quitada no primeiro
                            boleto bancário emitido pela ADMINISTRADORA, podendo ser todo dia 5 (cinco) ou 20 (vinte) de cada
                            mês conforme a opção definida no FORMULÁRIO EMPRESA da PROPOSTA DE ADESÃO.<br><br>
  
                            <b>6.1</b> Em caso de não pagamento do primeiro boleto em seu vencimento, o cadastro referente a Proposta
                            de Adesão será cancelado em até 30 (trinta) dias, não isentando a cobrança deste. Caso a EMPRESA
                            CONTRATANTE tenha interesse em efetuar a aquisição de um novo plano deverá procurar um corretor
                            e será necessário o preenchimento de uma nova Proposta de Adesão juntamente com o envio dos
                            documentos necessários.<br><br>
  
                            <b>6.2</b> A cobrança da taxa de corretagem não representa o pagamento da primeira mensalidade do plano de
                            assistência à saúde, e o início da vigência do plano e do Contrato somente se dará após o pagamento do
                            primeiro boleto de cobrança emitido pela ADMINISTRADORA na data escolhida na Proposta de Adesão.<br><br>
  
                            <b>7</b> A ADMINISTRADORA não se responsabilizará por quaisquer atos, promessas ou compromissos
                            efetuados por corretores que estejam em desacordo com as cláusulas expressas neste Contrato.<br><br>
  
                            <b>8</b> A EMPRESA CONTRATANTE poderá declinar este Contrato, sem nenhum ônus, desde que tal decisão
                            seja comunicada por escrito à ADMINISTRADORA no prazo máximo de 7 (sete) dias contados a partir da
                            data da assinatura deste instrumento, autorizando a cobrança da Taxa de Cadastramento e Implantação e
                            do valor mensal do benefício, caso esse prazo não seja observado. <br><br>
  
                            <b>9</b> O presente Contrato é passível de devolução caso não haja contato telefônico de pós-venda e précadastro
                            com o responsável da EMPRESA CONTRATANTE e os titulares responsáveis para confirmação
                            de dados. <br><br>
  
                            <b>10</b> Deve ser observada a importância de que todas as informações sejam verdadeiras para que não ocorram
                            prejuízos ou danos aos demais participantes e, de acordo com o Artigo 766 do Código Civil Brasileiro,';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais3);
  
      $condicoes_gerais4 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              se omitidas circunstâncias que possam influenciar na aceitação ou na mensalidade, poderá ensejar na
                              perda de todo e qualquer direito inerente à mesma. As informações prestadas na Declaração de Saúde
                              deverão ser absolutamente verdadeiras e completas, ficando a ADMINISTRADORA autorizada a solicitar,
                              a qualquer momento, documentação comprobatória das informações fornecidas. Em caso de fraude o
                              beneficiário será cancelado de imediato de acordo com o previsto no Art. 13, Inciso II da Lei nº 9.656/98.<br><br>
  
                              <b>11</b> O Contrato Coletivo Empresarial será renovado no mês de AGOSTO, por igual período, desde que não
                              ocorra denúncia por escrito por parte da OPERADORA ou da ADMINISTRADORA do Contrato. Em caso de
                              rescisão desse Contrato coletivo, a ADMINISTRADORA fará a comunicação desse fato com antecedência
                              mínima de 30 (trinta) dias.<br><br>
  
                              <b>DAS CARÊNCIAS</b><br><br>
  
                              <b>12</b> Os prazos de carência são períodos nos quais o(s) beneficiário(s) poderá(rão) realizar determinadas
                              coberturas desde que esteja em dia com o pagamento. Haverá prazos de carências para utilização do(s)
                              benefício(s) contados a partir da data de vigência e cobertura.
  
                              <table border="1" cellspacing="0" style="width: 100%; margin-left: 0px; text-align: center; font-size: 12px; margin-top: 10px;">
                                <tr>
                                 <th>PROCEDIMENTO</th>
                                 <th>CARÊNCIAS</td>
                                </tr>
  
                                <tr>
                                 <td>Consulta Eletiva</td>
                                 <td>24 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Consulta de Emergência/Urgência</td>
                                 <td>24 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Exames Básicos</td>
                                 <td>24 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Exames Especiais</td>
                                 <td>180 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Procedimentos Básicos</td>
                                 <td>24 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Procedimentos Especiais</td>
                                 <td>180 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Psicoterapia</td>
                                 <td>24 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Fonoaudiologia</td>
                                 <td>24 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Fisioterapia</td>
                                 <td>24 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Nutrição</td>
                                 <td>24 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Terapia Ocupacional</td>
                                 <td>24 horas</td>
                                </tr>
  
                                <tr>
                                 <td>Quimioterapia</td>
                                 <td>180 dias</td>
                                </tr>
  
                                <tr>
                                 <td>Diálise e Hemodiálise</td>
                                 <td>180 dias</td>
                                </tr>
  
                                <tr>
                                 <td>Radioterapia</td>
                                 <td>180 dias</td>
                                </tr>
  
                                <tr>
                                 <td>Parto a termo</td>
                                 <td>300 dias</td>
                                </tr>
  
                                <tr>
                                 <td>Internações</td>
                                 <td>180 dias</td>
                                </tr>
  
                                <tr>
                                 <td>Doenças/Lesões Preexistentes</td>
                                 <td>720 dias</td>
                                </tr>
                              </table>';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais4);
  
      $condicoes_gerais5 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                              <b>DAS DOENÇAS E LESÕES PREEXISTENTES</b>
  
                              <br><br><b>13</b> Doença ou lesão preexistente é aquela em que o beneficiário saiba ser ou ter sido portador no momento
                              da contratação do plano de saúde.
  
                              <br><br><b>13.1</b> Cobertura Parcial Temporária (CPT) é a suspensão, por um período ininterrupto de até 24 meses,
                              a partir da data da contratação ou adesão ao plano privado de assistência à saúde, da cobertura de
                              Procedimentos de Alta Complexidade (PAC), leitos de alta tecnologia e procedimentos cirúrgicos, desde
                              que relacionados exclusivamente às doenças e lesões preexistentes.
  
                              <br><br><b>13.2</b> O beneficiário deverá preencher, no momento da contratação, a Declaração de Saúde conforme
                              disposto na Resolução Normativa nº 162 de 17 de outubro de 2007 da ANS.
  
                              <br><br><b>14</b> Sendo constatada a existência de lesão ou doença preexistente que possa gerar necessidade de eventos
                              cirúrgicos, uso de leitos de alta tecnologia ou procedimentos de alta complexidade, o(s) beneficiário(s)
                              deverá(rão) cumprir a Cobertura Parcial Temporária (CPT) cujo prazo será de no máximo 24 (vinte e
                              quatro) meses a contar da vigência do beneficiário. Findado o prazo, a cobertura do plano passará a ser
                              integral, não cabendo qualquer tipo de agravo por doença ou lesão preexistente.
  
                              <br><br><b>15</b> Será considerado como comportamento fraudulento a omissão de doença ou lesão preexistente de
                              conhecimento prévio do beneficiário.
  
                              <br><br><b>15.1</b> Alegada a existência de doença ou lesão preexistente não declarada pelo beneficiário no preenchimento
                              da Declaração de Saúde, o beneficiário será imediatamente comunicado pela OPERADORA. Caso o
                              beneficiário não concorde com a alegação, a OPERADORA encaminhara a documentação pertinente à
                              Agência Nacional de Saúde Suplementar/ANS, e esta abrirá processo administrativo para investigação.
  
                              <br><br><b>15.2</b> Cumpre esclarecer que durante o período em que a ANS estiver analisando o referido processo
                              investigatório, a OPERADORA poderá realizar o procedimento pretendido normalmente. Entretanto, se
                              ao término do processo investigatório for constatada a omissão do beneficiário em relação as doenças ou
                              lesões preexistentes, este deverá ressarcir, integralmente, todas as despesas decorrentes do procedimento
                              realizado à OPERADORA.
  
                              <br><br>DA URGÊNCIA E DA EMERGÊNCIA
  
                              <br><br><b>16</b> Atendimento de Urgência e Emergência
                              Para efeitos desta cobertura, entende-se como atendimento de emergência aquele que implica no risco
                              imediato de vida ou de lesões irreparáveis para o paciente, caracterizado em declaração do médico
                              assistente. Como atendimento de urgência entende-se aquele resultante de acidente pessoal ou de
                              complicações no período gestacional.
  
                              <br><br><b>16.1</b> Para os casos de urgência e emergência a OPERADORA garantirá assistência médica no sentido da
                              preservação da vida, órgãos e funções.
  
                              <br><br><b>16.2</b> Nos planos hospitalares, em caso de Urgência e Emergência após o cumprimento de carência de
                              24 horas, ainda em cumprimento de período de carência para internação, ou na contratação do plano
                              ambulatorial após o cumprimento de carência de 24 horas, será garantido Cobertura e medicação, limitadas
                              até as primeiras 12 (doze) horas. Quando necessária, para a continuidade de internação do atendimento de
                              Urgência e Emergência, após este referido prazo das 12 horas, a cobertura cessará, sendo do contratante
                              a responsabilidade financeira, não cabendo ônus à OPERADORA. A remoção do paciente será realizada
                              pela OPERADORA, para uma unidade do Serviço Único de Saúde que disponha dos recursos necessários a garantir a continuidade do atendimento.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais5);
  
      $condicoes_gerais6 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                              <b>DA EXECUÇÃO DOS SERVIÇOS</b><br><br>
  
                              <b>17</b> Área de Abrangência e Locais de Atendimento
                              O referido plano possui abrangência geográfica em grupos de municípios do Estado do Rio Grande do Sul.<br><br>
  
                              <b>18</b> É obrigatória a apresentação da Cédula de Identificação com foto para usufruir dos atendimentos e
                              recursos deste Contrato.<br><br>
  
                              <b>19</b> A rede credenciada de prestadores coberta pelo plano de saúde deverá ser consultada através do site
                              da OPERADORA (https://www.lifedaysaude.com.br/). Fica estabelecido que o(s) beneficiário(s) e seus
                              dependentes utilizarão o plano na rede credenciada mediante apresentação da numeração de matrícula
                              do plano e/ou a carteira provisória expedida pela ADMINISTRADORA através dos canais de atendimento
                              e/ou o cartão definitivo (não obrigatório).<br><br>
  
                              <b>20</b> A OPERADORA se reserva, outrossim, o direito de modificar, extinguir ou realizar novos convênios de
                              credenciamento de profissionais, clínicas e pronto socorros, mantendo sempre o seu alto padrão técnico.<br><br>
  
                              <b>21</b> Na hipótese da substituição de estabelecimento hospitalar por outro equivalente, a OPERADORA
                              comunicará a Agência Nacional de Saúde Suplementar/ANS no prazo de 30 (trinta) dias de antecedência,
                              garantindo assim a continuidade da internação.<br><br>
  
                              <b>DA FORMAÇÃO DO PREÇO E DA MENSALIDADE<br><br></b>
  
                              <b>22</b> Após o fechamento do efetivo mensal, a ADMINISTRADORA enviará fatura correspondente aos
                              atendimentos/mensalidades realizados no mês, sendo de responsabilidade da EMPRESA CONTRATANTE
                              o pagamento da mesma na totalidade de BENEFICIÁRIOS inscritos, ressalvadas as hipóteses previstas
                              nos Art. 30 e 31 da Lei nº 9.656/98.<br><br>
  
                              <b>23</b> A EMPRESA CONTRATANTE efetuará a quitação mensal, na data de vencimento escolhida no ato
                              da assinatura deste Contrato, pelo número de BENEFICIÁRIOS vinculados a empresa, sendo os valores
                              determinados de acordo com a faixa etária e produto escolhido por cada usuário.<br><br>
  
                              <b>23.1</b> Será responsabilidade da EMPRESA CONTRATANTE avisar à ADMINISTRADORA o não recebimento
                              do boleto até 2 (dois) dias úteis antes do seu vencimento. Após a data de vencimento da fatura incidirão
                              multa de 2% sobre o valor do débito em atraso e juros de mora de 1,0% ao mês, estando a ADMINISTRADORA
                              autorizada a realizar cobrança, caso haja pendência financeira, através de SMS, cartas, e-mails ou qualquer
                              outro meio de comunicação legal.<br><br>
  
                              <b>23.2</b> Será cobrada a Taxa de Reemissão de boleto caso a solicitação da segunda via seja feita após a data
                              do vencimento original da mensalidade, e seu valor será de R$ 3,00 (três reais), podendo ser reajustado
                              conforme a base tarifária do banco emissor. Todos os comunicados que forem entregues com a fatura
                              serão considerados notificações extrajudiciais.<br><br>
  
                              <b>23.3</b> Caso não ocorra o pagamento em seu vencimento, a ADMINISTRADORA se reserva ao direito
                              de proceder a inclusão do número do CNPJ da EMPRESA CONTRATANTE nos cadastros dos órgãos
                              restritivos de crédito, caso haja atrasos superiores a 30 (trinta) dias, bem como de seu representante
                              legal, o qual obriga-se a observar e cumprir os prazos e condições de pagamento estipulados neste
                              Contrato, como também respeitar as normas e regulamentos do benefício, respondendo civil e
                              criminalmente por quaisquer danos morais e materiais eventualmente causados por si e pela utilização
                              indevida do plano de saúde.<br><br>
  
                              <b>24</b> Não haverá distinção quanto ao valor da prestação entre os BENEFICIÁRIOS que vierem a ser incluídos';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais6);
  
      $condicoes_gerais7 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                              no Contrato e aqueles a este já vinculado.<br><br>
  
                              <b>25</b> Todos os pagamentos mensais serão efetuados pela EMPRESA CONTRATANTE à ADMINISTRADORA
                              através de boleto bancário nos prazos de cobrança e na forma estabelecida nos documentos emitidos
                              pela ADMINISTRADORA, não realizando a mesma, em hipótese alguma, cobrança domiciliar. Para
                              qualquer outro meio de pagamento, o mesmo somente poderá ser efetuado com prévia autorização da
                              ADMINISTRADORA.<br><br>
  
                              <b>26</b> A EMPRESA CONTRATANTE reconhece que os valores estabelecidos neste Contrato são líquidos e
                              certos, legitimando a emissão de faturamento mensal em conformidade com esta Cláusula e procedimento
                              executivo nos casos de inadimplência com a inclusão, então, dos juros legais e das despesas processuais,
                              advocatícias e demais cominações legais.<br><br>
  
                              <b>DO FATOR MODERADOR<br><br></b>
  
                              <b>27</b> O referido plano contratado possui cobrança de COPARTICIPAÇÃO a qual será realizada no boleto junto
                              com a mensalidade conforme tabela abaixo:
  
                                <table border="1" cellspacing="0" style="width: 100%; text-align: center; font-size: 10px; margin-top: 10px;">
                                  <tr>
                                   <th rowspan="2">PROCEDIMENTO</th>
                                   <th colspan="6">COPARTICIPAÇÃO</th>
                                  </tr>
  
                                  <tr>
                                   <td>AMBULATORIAL</td>
                                   <td>CLÁSSICO - SEMIPRIVATIVO</td>
                                   <td colspan="4">CLÁSSICO - PRIVATIVO</td>
                                  </tr>
  
                                  <tr>
                                   <td>Consulta Eletiva</td>
                                   <td>R$ 25,00</td>
                                   <td>R$ 25,00</td>
                                   <td colspan="4">R$ 40,00</td>
                                  </tr>
  
                                  <tr>
                                   <td>Consulta de Emergência</td>
                                   <td>R$ 25,00</td>
                                   <td>R$ 25,00</td>
                                   <td colspan="4">R$ 40,00</td>
                                  </tr>
  
                                  <tr>
                                   <td>Psicoterapia</td>
                                   <td>R$ 25,00</td>
                                   <td>R$ 25,00</td>
                                   <td colspan="4">R$ 40,00</td>
                                  </tr>
  
                                  <tr>
                                   <td>Fonoaudiologia</td>
                                   <td>R$ 25,00</td>
                                   <td>R$ 25,00</td>
                                   <td colspan="4">R$ 40,00</td>
                                  </tr>
  
                                  <tr>
                                   <td>Nutrição</td>
                                   <td>R$ 25,00</td>
                                   <td>R$ 25,00</td>
                                   <td colspan="4">R$ 40,00</td>
                                  </tr>
  
                                  <tr>
                                   <td>Terapia Ocupacional</td>
                                   <td>R$ 25,00</td>
                                   <td>R$ 25,00</td>
                                   <td colspan="4">R$ 40,00</td>
                                  </tr>
  
                                  <tr>
                                   <td>Quimioterapia</td>
                                   <td>45% do limite de 300,00</td>
                                   <td>45% do limite de 300,00</td>
                                   <td colspan="4">45% do limite de 300,00</td>
                                  </tr>
  
                                  <tr>
                                   <td>Diálise e Hemodiálise</td>
                                   <td>45% do limite de 300,00</td>
                                   <td>45% do limite de 300,00</td>
                                   <td colspan="4">45% do limite de 300,00</td>
                                  </tr>
  
                                  <tr>
                                   <td>Radioterapia</td>
                                   <td>45% do limite de 300,00</td>
                                   <td>45% do limite de 300,00</td>
                                   <td colspan="4">45% do limite de 300,00</td>
                                  </tr>
                                </table><br><br>
  
                                <b>27.1</b> Os valores referentes a COPARTICIPAÇÃO também sofrerão reajustes no aniversário do contrato.<br><br>
  
                                <b>27.2</b>  As cobranças de COPARTICIPAÇÃO poderão ser realizadas em até 5 (cinco) anos, de sua utilização,
                                mesmo que o contrato tenha sido cancelado. Esta cobrança se basea no prazo em conformidade ao
                                atual código civil.<br><br>
  
                                <b>27.3</b>  Em havendo consulta com qualquer especialidade, no caso de falta não desmarcada com
                                antecedência mínima de 24 (vinte e quatro) horas, será cobrada pela falta, na fatura mensal, o
                                equivalente à taxa de consulta não comparecida, calculada em 30% do valor da consulta.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais7);
  
      $condicoes_gerais8 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                              DO REAJUSTE<br><br>
  
                              <b>28</b> A atualização dos valores dos custos mensais será efetuada anualmente, no mês de aniversário do
                              Contrato entre a ADMINISTRADORA e a OPERADORA, e ocorrerá no mês de AGOSTO de cada ano,
                              independente da data da adesão ao Contrato, pela variação do IPCA, que será apurado no período de 12
                              meses consecutivos, sendo aplicada a todos os usuários ativos no Contrato, independentemente da idade.<br><br>
  
                              <b>28.1</b> O percentual reajustado será informado à ANS até 30 (trinta) dias após a data da aplicação.<br><br>
  
                              <b>29</b> Independentemente da data de inclusão dos usuários, os valores de suas contraprestações sofrerão o
                              primeiro reajuste integral na data de aniversário (vigência) do Contrato ou quando em razão de mudança
                              de faixa etária, migração e adaptação do Contrato à Lei nº 9.656/98;<br><br>
  
                              <b>30</b> Na hipótese de se constatar a necessidade de aplicação do reajuste por sinistralidade, este será negociado
                              de comum acordo entre a ADMINISTRADORA e a OPERADORA, sendo que o nível de sinistralidade da
                              carteira terá por base a proporção entre as despesas assistenciais e as receitas diretas do plano, apuradas
                              no período de 12 (doze) meses consecutivos, anteriores à data base de aniversário considerada como o
                              mês de assinatura do contrato entre a EMPRESA CONTRATANTE e a OPERADORA.<br><br>
  
                              <b>30.1</b> Fica estabelecido para este Contrato o ponto de equilíbrio a ser considerado para eventual sinistralidade
                              em 65% (sessenta e cinco por cento).<br><br>
  
                              <b>31</b> Nos casos de aplicação de reajuste por sinistralidade, o mesmo será procedido de forma complementar
                              ao especificado no Item 29 e nas mesmas datas.<br><br>
  
                              <b>32</b> Será calculado valor único de percentual de reajuste para o agrupamento ao qual está agregado o Contrato.<br><br>
  
                              <b>DAS FAIXAS ETÁRIAS</b><br><br>
  
                              <b>33</b> Variação do preço em razão da faixa etária
                              Havendo alteração de faixa etária de beneficiário inscrito no presente Contrato, a contraprestação
                              pecuniária será reajustada no mês subsequente ao da ocorrência, de acordo com os valores da tabela
                              abaixo, que se acrescentarão sobre o valor da última contraprestação pecuniária, observadas a seguintes
                              condições, conforme determina o Art. 3º, Incisos I e II da RN nº 63/03.
  
                              <table border="1" cellspacing="0" style="width: 100%; text-align: center; font-size: 10px; margin-top: 10px;">
                                <tr>
                                 <th rowspan="2">PROCEDIMENTO</th>
                                 <th colspan="3" width="100px">Plano Ambulatorial (ANS 478.718/17-8)</th>
                                 <th colspan="3" width="100px">Plano Ambulatorial + Hospitalar (ANS 478.720/17-0)</th>
                                </tr>
  
                                <tr>
                                 <td>02 a 05 (Vidas)</td>
                                 <td>06 a 29 (Vidas)</td>
                                 <td>30 a 99 (Vidas)</td>
                                 <td>02 a 05 (Vidas)</td>
                                 <td>06 a 29 (Vidas)</td>
                                 <td>30 a 99 (Vidas)</td>
                                </tr>
  
                                <tr>
                                 <td>00 a 18</td>
                                 <td>15,87%</td>
                                 <td>15,89%</td>
                                 <td>15,91%</td>
                                 <td>14,77%</td>
                                 <td>14,78%</td>
                                 <td>14,77%</td>
                                </tr>
  
                                <tr>
                                 <td>19 a 23</td>
                                 <td>9,34% </td>
                                 <td>9,34% </td>
                                 <td>9,34% </td>
                                 <td>29,88% </td>
                                 <td>29,87% </td>
                                 <td>29,89% </td>
                                </tr>
  
                                <tr>
                                 <td>24 a 28</td>
                                 <td>11,05% </td>
                                 <td>11,06% </td>
                                 <td>11,06% </td>
                                 <td>21,99% </td>
                                 <td>21,99% </td>
                                 <td>21,99% </td>
                                </tr>
  
                                <tr>
                                 <td>29 a 33</td>
                                 <td>11,89% </td>
                                 <td>11,89% </td>
                                 <td>11,88% </td>
                                 <td>9,98% </td>
                                 <td>10,02% </td>
                                 <td>10,02% </td>
                                </tr>
  
                                <tr>
                                 <td>34 a 38</td>
                                 <td>15,44% </td>
                                 <td>15,46% </td>
                                 <td>15,46% </td>
                                 <td>5,00% </td>
                                 <td>5,01% </td>
                                 <td>5,01% </td>
                                </tr>
  
                                <tr>
                                 <td>39 a 43</td>
                                 <td>28,00% </td>
                                 <td>24,56% </td>
                                 <td>24,56% </td>
                                 <td>16,62% </td>
                                 <td>16,62% </td>
                                 <td>16,62% </td>
                                </tr>
  
                                <tr>
                                 <td>44 a 48</td>
                                 <td>18,12% </td>
                                 <td>18,13% </td>
                                 <td>18,13% </td>
                                 <td>4,30% </td>
                                 <td>4,30% </td>
                                 <td>4,30% </td>
                                </tr>
  
                                <tr>
                                 <td>49 a 53</td>
                                 <td>18,12% </td>
                                 <td>18,13% </td>
                                 <td>18,13% </td>
                                 <td>4,30% </td>
                                 <td>4,30% </td>
                                 <td>4,30% </td>
                                </tr>
  
                                <tr>
                                 <td>54 a 58</td>
                                 <td>37,12% </td>
                                 <td>41,86% </td>
                                 <td>41,86% </td>
                                 <td>38,99% </td>
                                 <td>39,00% </td>
                                 <td>39,00% </td>
                                </tr>
  
                                <tr>
                                 <td> > de 59</td>
                                 <td>41,85% </td>
                                 <td>37,13% </td>
                                 <td>37,13% </td>
                                 <td>63,17% </td>
                                 <td>63,17% </td>
                                 <td>63,17% </td>
                                </tr>
                              </table>';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais8);
  
      $condicoes_gerais9 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                              <b>DAS REGRAS PARA INSTRUMENTOS JURÍDICOS DE PLANOS COLETIVOS POR ADESÃO<br><br></b>
  
                              <b>Termo de Permanência</b><br>
                              <b>34</b> O beneficiário titular que for demitido ou exonerado sem justa causa, decorrente da sua relação de
                              trabalho com a EMPRESA CONTRATANTE, terá o direito de formalizar Termo de Permanência conforme
                              as regras dos Art. 30 e 31 da Lei nº 9.656/98 e RN nº 279 da ANS.<br>
  
                              <b>35</b> A OPERADORA assegura ao beneficiário titular e seus dependentes vinculados já inscritos o direito de
                              manter sua condição de beneficiário no plano de saúde, nas mesmas condições de cobertura assistencial
                              de que gozava quando da vigência do Contrato de Trabalho quando este for desligado ou exonerar-se da
                              empresa, sem justa causa, através da formalização do Termo de Permanência.<br>
  
                              <b>36</b> Pagamento
                              O beneficiário que realizar o Termo de Permanência assumirá o pagamento integral diretamente à
                              OPERADORA que lhe fornecerá as devidas instruções na forma do Art 30 da Lei nº 9.656/98 c/c RN nº
                              179/2011 da ANS.
  
                              37 Requisitos para realização do Termo de Permanência<br>
                              O termo de permanência só será permitido quando cumprido os seguintes requisitos legais:<br>
  
                              a) o ex-empregado formalizar o pedido de realização do Termo de Permanência no prazo máximo
                              de 30 (trinta) dias a contar da assinatura do Formulário de Exclusão formalizado junto à EMPRESA
                              CONTRATANTE, que será entregue no ato do comunicado de rescisão do Contrato de Trabalho.<br>
  
                              b) contribuição do beneficiário titular pelo plano de saúde, através de desconto em Folha de Pagamento, no
                              qual comprove que este contribuiu total ou parcialmente pelo plano em decorrência do vínculo empregatício,
                              com exceção dos valores realizados à contribuição de dependentes, agregados e coparticipação.<br>
  
                              c) assuma o pagamento integral do plano, conforme a tabela de valores e suas atualizações, estabelecida
                              no contrato principal firmado com a EMPRESA CONTRATANTE.<br>
  
                              <b>37</b> Requisitos para realização do Termo de Permanência<br>
                              O termo de permanência só será permitido quando cumprido os seguintes requisitos legais:<br>
                              a) o ex-empregado formalizar o pedido de realização do Termo de Permanência no prazo máximo
                              de 30 (trinta) dias a contar da assinatura do Formulário de Exclusão formalizado junto à EMPRESA
                              CONTRATANTE, que será entregue no ato do comunicado de rescisão do Contrato de Trabalho.<br>
  
                              b) contribuição do beneficiário titular pelo plano de saúde, através de desconto em Folha de Pagamento, no
                              qual comprove que este contribuiu total ou parcialmente pelo plano em decorrência do vínculo empregatício,
                              com exceção dos valores realizados à contribuição de dependentes, agregados e coparticipação.<br>
  
                              c) assuma o pagamento integral do plano, conforme a tabela de valores e suas atualizações, estabelecida
                              no contrato principal firmado com a EMPRESA CONTRATANTE.<br>
  
                              <b>38</b> Período de Manutenção
                              O período de manutenção do beneficiário no Termo de Permanência será de 1/3 (um terço) do tempo de
                              permanência no seu plano anterior, com um mínimo assegurado de 06 (seis) meses e um máximo de 24
                              (vinte e quatro) meses.<br>
  
                              <b>39</b> Extensão ao Grupo Familiar
                              O Termo de Permanência poderá ser celebrado individualmente ou estendido a todo grupo familiar do
                              beneficiário, quando inscritos durante a vigência do Contrato de Trabalho.<br>
  
                              <b>39.1</b> É permitida a inclusão junto ao Termo de Permanência somente para novo conjuge e filhos do exempregado
                              demitido, exonerado ou aposentado.<br>
  
                              <b>40</b> Condições de perda do Termo de Permanência
                              a) quando do término da contagem de prazo de permanência estipulado no momento da assinatura do
                              termo;
                              b) pela admissão do beneficiário em novo emprego que lhe possibilite ingresso em novo plano; ou
                              c) cancelamento do plano coletivo empresarial ao qual o beneficiário demitido encontrava-se vinculado.<br>
  
                              <b>41</b> Morte do Titular
                              Em caso de morte do titular, o direito de permanência é assegurado aos dependentes cobertos pelo
                              plano privado coletivo de assistência à saúde, desde que assumam as mensalidades correspondentes aos
                              dependentes que optarem por permanecerem com o plano.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais9);
  
      $condicoes_gerais10 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
                              <b>42</b> Aposentados<br>
                              Ao aposentado que contribuiu comprovadamente para o plano contratado decorrente de vínculo
                              empregatício, pelo prazo mínimo de 10 (dez) anos, a OPERADORA assegura ao beneficiário titular e seus
                              dependentes vinculados, o direito de manutenção como beneficiário no plano de saúde nas mesmas
                              condições de cobertura assistencial de que gozava quando da vigência do Contrato de Trabalho, desde
                              que assuma junto à OPERADORA o pagamento integral das mensalidades, na forma do Art. 31 da Lei nº
                              9.656/98.<br><br>
  
                              <b>42.1</b> Ao aposentado que contribuiu comprovadamente para o plano, por período inferior a 10 (dez) anos,
                              é assegurado o direito de realizar o Termo de Permanência à razão de 1 (um) ano para cada ano de
                              contribuição, desde que assuma o pagamento integral do plano.<br><br>
  
                              <b>42.2</b> O aposentado deve optar pela manutenção do benefício no prazo máximo de 30 dias a contar da
                              assinatura do Formulário de Exclusão formalizado junto à EMPRESA CONTRATANTE que será entregue
                              no ato do comunicado de rescisão contratual;<br><br>
  
                              <b>43</b> Aposentado que permanecer trabalhando:<br>
                              O aposentado que continuar trabalhando na mesma empresa e venha a se desligar desta, é garantido o
                              direito de manter sua condição de beneficiário através do Termo de Permanência, devendo este optar pela
                              manutenção no prazo máximo de 30 (trinta) dias a contar da data de seu desligamento junto à EMPRESA
                              CONTRATANTE e da assinatura do Formulário de Exclusão;<br><br>
  
                              <b>43.1</b> O Termo de Permanência também poderá ser estendido aos dependentes já inscritos do empregado
                              aposentado que continuou trabalhando na mesma empresa e que veio a falecer antes de se desligar,
                              cabendo a estes optar pela manutenção no prazo máximo de 30 (trinta) dias a contar do óbito e da
                              assinatura do Formulário de Exclusão.<br><br>
  
                              <b>44</b> Cancelamento do Plano Empresarial:
                              Em caso de cancelamento dos planos coletivos empresariais terão os BENEFICIÁRIOS a garantia de
                              continuar com o plano de saúde na modalidade coletivo por adesão ou empresarial, sem a necessidade do
                              cumprimento de novos prazos de carências, conforme Súmula Normativa 21 da ANS, desde que comunique
                              a OPERADORA no prazo máximo de 30 (trinta) após o seu término.<br><br>
  
                              <b>45</b> Formas de contribuições permitidas:
                              Nos planos custeados integralmente pela EMPRESA CONTRATANTE, quando o titular não participar
                              financeiramente do plano durante o período que mantiver o vínculo empregatício, este não terá direito
                              ao Termo de Permanência. Não é considerada contribuição a coparticipação do beneficiário, única e
                              exclusivamente em procedimentos, como fator de moderação na utilização dos serviços de assistência
                              médica e/ou hospitalar.<br><br>
  
                              <b>46</b> O direito assegurado ao beneficiário titular, demitido ou aposentado, não exclui vantagens obtidas
                              pelos empregados decorrentes de negociações coletivas de trabalho.<br><br>
  
                              <b>DAS CONDIÇÕES DE PERDA DA QUALIDADE DE BENEFICIÁRIO</b><br><br>
  
                              <b>47</b> Compete à EMPRESA CONTRATANTE, na vigência deste Contrato, comunicar imediatamente as
                              ocorrências de demissões realizadas no período, bem como o recolhimento e a devolução das respectivas
                              Cédulas de Identificação, sendo que caberá somente à EMPRESA CONTRATANTE solicitar a exclusão dos
                              BENEFICIÁRIOS.<br><br>
  
                              <b>47.1</b> As solicitações de exclusões devem conter a assinatura e o carimbo do representante legal da
                              EMPRESA CONTRATANTE;';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais10);
  
      $condicoes_gerais11 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                              <b>47.2</b> As exclusões serão realizadas após 05 (cinco) dias do recebimento pela OPERADORA;<br><br>
  
                              <b>47.3</b> A EMPRESA CONTRATANTE será responsável pelos atendimentos prestados aos usuários demitidos
                              ou excluídos cujos nomes não tenham sido comunicados a OPERADORA, em tempo hábil, cabendo-lhe
                              indenizar os seus custos;<br><br>
  
                              <b>47.4</b> Na falta de comunicação em tempo oportuno, da inclusão ou da exclusão de BENEFICIÁRIOS, a
                              fatura se baseará nos dados disponíveis, sendo os eventuais acertos realizados na fatura subsequente.<br><br>
  
                              <b>48</b> Cancelamento a pedido – RN 412
                              O benecifiário que desejar realizar o cancelamento de seu plano de saúde, deverá solicitar a exclusão junto
                              a empresa CONTRATANTE.<br><br>
  
                              <b>48.1</b> A EMPRESA CONTRATANTE deverá enviar a solicitação de cancelamento a ADMINISTRADORA
                              através do e-mail rempresarial@grupocontem.com.br, encaminhando a solicitação por escritopelo
                              responsável pela EMPRESA CONTRATANTE.<br><br>
  
                              <b>48.2</b> Caso o beneficiário solicite a portabilidade de seu plano para outra OPERADORA, ao concluir a
                              portabilidade, o beneficiário deverá solicitar o cancelamento do seu vínculo com a ADMINISTRADORA ou
                              OPERADORA de origem no prazo de 5 (cinco) dias a partir da data do início da vigência do seu vínculo
                              com o plano de destino.<br><br>
  
                              <b>49</b> A OPERADORA só poderá cancelar a assistência à saúde dos BENEFICIÁRIOS, sem a anuência da
                              EMPRESA CONTRATANTE, nos seguintes casos:<br><br>
  
                              a) fraude comprovada mediante notificação formal ao beneficiário;<br>
                              b) perda do vínculo do titular com a EMPRESA ou de dependência, ressalvado o disposto nos Art. 30 e 31
                              da Lei nº 9.656/98; ou<br>
                              c) agressão verbal ou física aos colaboradores da OPERADORA.<br><br>
  
                              <b>49.1</b> Para todos esses casos haverá comunicação formal da decisão à EMPRESA CONTRATANTE pela
                              OPERADORA.<br><br>
  
                              <b>DA RESCISÃO<br><br></b>
  
                              <b>50</b> O presente Contrato poderá ser rescindido nas hipóteses de fraude ou não pagamento da taxa mensal,
                              por período superior a 30 (trinta) dias consecutivo ou não, a cada 12 (doze) meses de vigência do Contrato,
                              cabendo à ADMINISTRADORA notificar a EMPRESA CONTRATANTE.<br><br>
  
                              <b>51</b> A omissão de informações ou fornecimento de informações incorretas ou inverídicas pela EMPRESA
                              CONTRATANTE para auferir vantagens próprias ou para seus usuários é reconhecida como violação ao
                              Contrato, permitindo à ADMINISTRADORA e à OPERADORA realizar a rescisão do Contrato por fraude.<br><br>
  
                              <b>52</b> Caso não ocorra a quitação da mensalidade em até 05 (cinco) dias a contar da data do vencimento
                              original do boleto bancário, poderá ocorrer a suspensão do(s) beneficiários(as) e a utilização somente
                              será reestabelecida a partir da quitação integral do(s) valor(es) pendente(s) acrescido( s) dos encargos
                              supracitados.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais11);
  
      $condicoes_gerais12 .= '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                              <b>52.1</b> O restabelecimento do serviço em caso de suspensão caso não ocorra a quitação da mensalidade
                              em até 05 (cinco) dias a contar da data do vencimento original, dependerá da comprovação da baixa
                              bancária.<br><br>
  
                              <b>DAS DISPOSIÇÕES GERAIS</b><br><br>
  
                              <b>53</b> Somente será possível postular nova adesão pela EMPRESA CONTRATANTE, mediante: (I) aceitação
                              pela ADMINISTRADORA, (II) quitação de eventuais débitos anteriores junto à ADMINISTRADORA,
                              mesmo que seja de Contrato de outra OPERADORA, e (III) cumprimento de novos prazos de carência,
                              independentemente do período anterior em que permaneceu no Contrato Coletivo.<br><br>
  
                              <b>54</b> Autorizo, expressamente, ressalvada as formas regulamentares de notificação, receber através de
                              e-mail, SMS e WhatsApp, notificações de cancelamento por inadimplência ao atingir 30 (trinta) dias em
                              atraso no pagamento, rescisão de contrato, inadimplência, reajuste anual, aviso de cobrança, migração e
                              demais avisos.<br><br>
  
                              <b>55</b> O Kit de Implantação, composto da Carteira do Plano e da Carta de Boas Vindas, será enviado ao
                              endereço de correspondência em até 30 (trinta) dias da data de vigência do Contrato através dos Correios
                              ou por empresa terceirizada. Fica excluído qualquer tipo de reembolso.<br><br>
  
                              <b>56</b> Para solicitar a 2ª via da carteira do plano de saúde será cobrado o valor da Taxa de Reemissão de
                              carteira (R$ 35,00 (trinta e cinco reais) por carteira).<br><br>
  
                              <b>57</b> O foro para dirimir quaisquer questões oriundas do presente contrato será o do Porto Alegre/RS, excluindo
                              qualquer outro.
  
                              <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; margin-top: 30px;">
                              <b>Dados da Empresa Contratante </b></div>
                              <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 0px;">
                                <tr>
                                 <td> <b>CNPJ: </b>'.$dados['cnpj'].' </td>
                                 <td> <b>Razão Social: </b>'.$dados['razao_social'].'</td>
                                </tr>
                              </table>
  
                              <table cellspacing="-1" style="width: 100%; font-size: 12px; margin-top: 100px;">
                                <tr>
                                 <td><center> ________________________________________,____,____,________ </center></td>
                                 <td><center> ______________________________________________________ </center></td>
                                </tr>
  
                                <tr>
                                 <td> <center>Local e data</center> </td>
                                 <td> <center>Assinatura do sócio ou representante legal da empresa</center> </td>
                                </tr>
                              </table>
  
                              <div style="text-align: center; margin-top: 30px; width: 100%;">
                                <img src="https://www.grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/cond_gerais/contato_contem.png">
                              </div>';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais12);
  
    } else if($dados['operadora'] == 'CEMERU') {
      $condicoes_gerais1 = '<div style="text-align: justify; font-size: 13px;">
                            <br><div style="margin-top: 55px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; font-size: 14px; text-align: left; padding: 5px; background-color: #d3d3d3">
                           <b>Condições Gerais</b></div><br>
                           <b> DAS CONDIÇÕES GERAIS </b>
  
                           <br><br>
                            <b>1.</b> Pelo presente instrumento particular, CONTÉM ADMINISTRADORA DE PLANOS DE SAÚDE LTDA,
                            utilizando como nome fantasia CONTÉM ADMINISTRADORA, inscrita no CNPJ/MF sob nº 13.286.268/0001-
                            83 e na ANS sob nº 41832-3, situada na Rua do Carmo, nº 08 - 10º andar, Centro - Rio de Janeiro/RJ - CEP
                            20011-020, denominada neste ato ADMINISTRADORA ESTIPULANTE; e AMESC–ASSOCIAÇÃO MÉDICA
                            ESPÍRITA CRISTÃ, utilizando como nome fantasia CEMERU, inscrita no CNPJ/MF sob nº 68.668.045/0001-
                            72 e na ANS sob nº 40108-1, situada na Rua Viúva Dantas, nº 720, Campo Grande - Rio de Janeiro/RJ
                            - CEP 23052-090, denominada neste ato OPERADORA, vêm celebrar o presente Contrato de Adesão
                            na modalidade Coletivo Empresarial, de forma a permitir que os sócios, executivos e empregados da
                            EMPRESA CONTRATANTE, bem como seus respectivos dependentes, possam usufruir da cobertura
                            médico-assistencial prevista neste Contrato, em conformidade com as Resoluções Normativas nº 195 e
                            196 da Agência Nacional de Saúde Suplementar/ANS.<br><br>
  
                            <b>DO OBJETO DO CONTRATO</b><br><br>
  
                            <b>1.1</b> O objeto deste Contrato é pactuar a adesão de Pessoa Jurídica, permitindo a ela, consequentemente,
                            proceder a inclusão das pessoas físicas a ela vinculadas por relação empregatícia ou estatutária para que
                            tenham cobertura à prestação continuada de serviços e/ou cobertura de custos assistenciais na forma de
                            Plano Privado de Assistência à Saúde prevista no Inciso I, do Art 1º da Leiº 9.656/98, visando a assistência
                            médica hospitalar com a cobertura de todas as doenças da “Classificação Estatística Internacional de
                            Doenças e Problemas com a Saúde”, da Organização Mundial de Saúde/OMS, obedecendo o “Rol de
                            Procedimentos e Eventos em Saúde” editado pela Agência Nacional de Saúde Suplementar/ANS, vigente
                            à época do evento.
  
                            <b>DOS BENEFICIÁRIOS DO CONTRATO</b><br><br>
  
                            <b>2.</b> Pessoas aptas a utilizar os serviços<br>
                            O Plano de Saúde Coletivo Empresarial é aquele que oferece cobertura da atenção prestada à população
                            delimitada e vinculada à EMPRESA CONTRATANTE, por relação empregatícia ou estatutária.<br><br>
  
                            <b>2.1</b> Serão considerados beneficiários da prestação de serviços o titular, pertencente ao corpo funcional
                            da EMPRESA CONTRATANTE, podendo ainda, serem inscritos pelo titular seus dependentes, assim
                            constituídos:<br><br>
  
                            a) o cônjuge ou companheiro(a) havendo união estável na forma da lei, do mesmo sexo ou do sexo oposto;<br>
                            b) os enteados, os filhos até 40 (quarenta) anos de idade incompletos (39 anos, 11 meses e 29 dias) ou
                            de qualquer idade se inválidos física ou mentalmente em caráter permanente, mediante comprovação de
                            incapacidade;<br>
                            c) os tutelados(as) e curatelados(as), menor sob guarda com o respectivo termo de tutela e curatela ou
                            guarda nos limites etários definidos nesta cláusula.<br><br>
  
                            <b>2.2</b> Também serão considerados beneficiários da prestação de serviços o titular, pertencente ao corpo
                            funcional da EMPRESA CONTRATANTE, podendo ainda, serem inscritos pelo titular seus dependentes,
                            assim constituídos:<br><br>
  
                            a) os sócios da EMPRESA CONTRATANTE<br>
                            b) os Administradores da EMPRESA CONTRATANTE<br>
                            c) os demitidos ou aposentados que tenham sido vinculados anteriormente à pessoa jurídica<br>
                            d) os trabalhadores temporários;<br>
                            e) os estagiários e menores aprendizes.<br>';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais1);
  
      $condicoes_gerais2 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            <b>2.3</b> A adesão do grupo familiar dependerá da participação do titular no plano de saúde.
  
                            <b>2.4</b> Cadastramento de beneficiários:<br>
                            Para uso dos benefícios previstos neste Contrato é indispensável o cadastramento prévio dos beneficiários
                            titulares e dependentes junto à ADMINISTRADORA e à OPERADORA.<br>
  
                            <b>2.4.1</b> Haverá cobertura contratual ao recém-nascido, filho natural ou adotivo do beneficiário, ou do seu
                            dependente, durante os primeiros 30 (trinta) dias após o parto ou adoção, independente do cadastramento,
                            sendo condicionadas as carências já cumpridas pelo beneficiário titular, sendo vedada qualquer alegação
                            de DLP ou aplicação de CPT ou Agravo;<br>
  
                            <b>2.4.2</b> No caso de inscrição do recém-nascido (filho natural ou adotivo) do beneficiário, dentro do prazo
                            máximo de 30 (trinta) dias do nascimento/adoção, este ingressará no plano com as mesmas carências já
                            cumpridas até a data da inscrição pelo titular;<br>
  
                            <b>2.4.3</b> No caso de inscrição de filho adotivo até 12 anos de idade haverá aproveitamento das carências já
                            cumpridas pelo beneficiário adotante;<br>
  
                            <b>2.4.4</b> O prazo para apresentação da documentação comprobatória da condição de universitário, a fim de
                            aproveitar o cumprimento de carência e CPT, será de 30 (trinta) dias a contar da data que completar a
                            maioridade prevista no Item 2.1 letra “b”.<br>
  
                            <b>2.5</b> As inclusões se efetivarão dentro do mês de sua comunicação, sendo observado o prazo máximo de
                            10 (dez) dias úteis contados do recebimento da solicitação para o cadastramento.<br>
  
                            <b>2.6</b> A EMPRESA CONTRATANTE fornecerá a relação nominal dos beneficiários que deverão ser vinculados
                            ao plano e os enviará em formulários apropriados a serem fornecidos pela AD;<br>
  
                            <b>2.6.1</b> Será necessária a comprovação da dependência dos familiares designados em relação aos seus
                            funcionários ou estatutários conforme Cláusulas 2.1 e 2.2.<br>
  
                            <b>2.6.2</b> É obrigatório o vínculo empregatício ou estatutário entre o beneficiário titular e a EMPRESA
                            CONTRATANTE, sendo que tanto a OPERADORA quanto a ADMINISTRADORA se reservam no direito
                            de exigir documentação comprobatória durante o período de vigência do contrato.<br>
  
                            <b>2.7</b> A EMPRESA CONTRATANTE informará, mensalmente, à ADMINISTRADORA, em formulários próprios,
                            o efetivo mensal do corpo funcional e seus dependentes;<br>
  
                            <b>2.7.1</b> O efetivo mensal será calculado com base no número de beneficiários cadastrados pela EMPRESA
                            CONTRATANTE;<br>
  
                            <b>2.8</b> As adesões dos beneficiários titulares e dependentes serão automáticas na data da contratação do
                            plano ou para as adesões posteriores, no ato da vinculação do beneficiário à EMPRESA CONTRATANTE;<br>
  
                            <b>3</b> A EMPRESA CONTRATANTE declara ser a única responsável pelos documentos e informações fornecidas
                            por ela e de seu corpo de beneficiário(s) e dependente(s) e sobre toda e qualquer circunstância que possa
                            influenciar na aceitação deste Contrato e na manutenção ou no valor mensal do mesmo, sabendo que
                            omissões ou dados errôneos acarretarão a perda de todos os direitos, bem como o(s) do(s) beneficiário(s)
                            dependente(s).<br><br>
  
                            <b>3.1</b> A EMPRESA CONTRATANTE deverá apresentar a documentação descrita abaixo:<br>
                            • Contrato Social e/ou última Alteração Contratual;<br>
                            • cópia do comprovante de endereço da EMPRESA CONTRATANTE (água, luz ou telefone);<br>
                            • comprovante de vínculo de todos os beneficiários (GFIP e/ou SEFIP);<br>
                            • CTPS, Ficha de Registro ou Contrato de Trabalho/Estágio para recém contratados e que ainda não
                            constam na GFIP e SEFIP;';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais2);
  
      $condicoes_gerais3 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            • cópia da Identificação Civil do(s) beneficiário(s) – RG;<br>
                            • cópia do CPF (inclusive para dependentes maiores de 18 anos) do(s) beneficiário(s);<br>
                            • cópia da Certidão de Casamento ou Declaração de União Estável do(s) beneficiário(s);<br>
                            • cópia da Certidão de Nascimento ou Certidão Tutelar do(s) beneficiário(s).<br><br>
  
                            <b>4</b> Por ser tratar de um Contrato Coletivo Empresarial, a EMPRESA CONTRATANTE e seus beneficiários
                            com vínculo empregatício e estatutário, além de seus dependentes, está sujeita às condições contratuais
                            aqui expressas, não se submetendo às regras inerentes aos contratos individuais, ficando outorgados à
                            ADMINISTRADORA amplos poderes para representar, assim como seu(s) beneficiário(s) dependente(s),
                            perante a OPERADORA e outros órgãos, em especial a ANS, no cumprimento e/ou nas alterações deste
                            benefício, bem como nos reajustes dos seus valores mensais.<br><br>
  
                            <b>4.1</b> A ADMINISTRADORA, na defesa do interesse de seus beneficiários, poderá alterar a OPERADORA que
                            atende este Contrato, independente de prévio aviso aos seus consumidores.<br><br>
  
                            <b>DA COBERTURA E PROCEDIMENTOS GARANTIDOS</b><br><br>
  
                            <b>5</b> A prestação de serviços de assistência médica e afins será prestada de acordo com as coberturas e
                            segmentações do “Rol de Procedimentos da ANS” vigente, não cabendo reembolso nos procedimentos
                            não cobertos por este rol.<br><br>
  
                            <b>DA VIGÊNCIA, DA VALIDADE E DA RESCISÃO DO CONTRATO</b><br><br>
  
                            <b>6</b> Após a aceitação deste Contrato sua vigência e do(s) seu(s) dependente(s), se houver, estará
                            impreterivelmente condicionada ao pagamento do valor total da mensalidade a ser quitada no primeiro
                            boleto bancário emitido pela ADMINISTRADORA, podendo ser todo dia 1º (primeiro), 10 (dez) ou 20
                            (vinte) de cada mês conforme a opção definida no FORMULÁRIO EMPRESA da PROPOSTA DE ADESÃO.<br><br>
  
                            <b>6.1</b> Em caso de não pagamento do primeiro boleto em seu vencimento, o cadastro referente a Proposta
                            de Adesão será cancelado em até 30 (trinta) dias, não isentando a cobrança deste. Caso a EMPRESA
                            CONTRATANTE tenha interesse em efetuar a aquisição de um novo plano deverá procurar um corretor
                            e será necessário o preenchimento de uma nova Proposta de Adesão juntamente com o envio dos
                            documentos necessários.<br><br>
  
                            <b>6.2</b> A cobrança da taxa de corretagem não representa o pagamento da primeira mensalidade do plano de
                            assistência à saúde, e o início da vigência do plano e do Contrato somente se dará após o pagamento do
                            primeiro boleto de cobrança emitido pela ADMINISTRADORA na data escolhida na Proposta de Adesão.<br><br>
  
                            <b>7</b> A ADMINISTRADORA não se responsabilizará por quaisquer atos, promessas ou compromissos
                            efetuados por corretores que estejam em desacordo com as cláusulas expressas neste Contrato.<br><br>
  
                            <b>8</b> A EMPRESA CONTRATANTE poderá declinar este Contrato, sem nenhum ônus, desde que tal decisão
                            seja comunicada por escrito à ADMINISTRADORA no prazo máximo de 7 (sete) dias contados a partir da
                            data da assinatura deste instrumento, autorizando a cobrança da Taxa de Cadastramento e Implantação e
                            do valor mensal do benefício, caso esse prazo não seja observado.<br><br>
  
                            <b>9</b> O presente Contrato é passível de devolução caso não haja contato telefônico de pós-venda e précadastro
                            com o responsável da EMPRESA CONTRATANTE e os titulares responsáveis para confirmação
                            de dados.<br><br>
  
                            <b>10</b> Deve ser observada a importância de que todas as informações sejam verdadeiras para que não ocorram
                            prejuízos ou danos aos demais participantes e, de acordo com o Artigo 766 do Código Civil Brasileiro,';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais3);
  
      $condicoes_gerais4 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            se omitidas circunstâncias que possam influenciar na aceitação ou na mensalidade, poderá ensejar na
                            perda de todo e qualquer direito inerente à mesma. As informações prestadas na Declaração de Saúde
                            deverão ser absolutamente verdadeiras e completas, ficando a ADMINISTRADORA autorizada a solicitar,
                            a qualquer momento, documentação comprobatória das informações fornecidas. Em caso de fraude o
                            beneficiário será cancelado de imediato de acordo com o previsto no Art. 13, Inciso II da Lei nº 9.656/98.<br><br>
  
                            <b>11</b> O Contrato Coletivo Empresarial será renovado no mês de AGOSTO, por igual período, desde que não
                            ocorra denúncia por escrito por parte da OPERADORA ou da ADMINISTRADORA do Contrato. Em caso
                            de rescisão desse Contrato coletivo, a ADMINISTRADORA fará a comunicação desse fato.<br><br>
  
                            <b>DAS CARÊNCIAS</b><br><br>
  
                            <b>12</b> Os prazos de carência são períodos nos quais o(s) beneficiário(s) poderá(rão) realizar determinadas
                            coberturas desde que esteja em dia com o pagamento. Haverá prazos de carências para utilização do(s)
                            benefício(s) contados a partir da data de vigência e cobertura.
  
                            <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; text-align: center; margin-top: 5px;">
                              <tr>
                                <th>ITENS </th>
                                <th>GRUPOS </th>
                                <th>PROCEDIMENTOS </th>
                                <th>CARÊNCIAS NORMAIS </th>
                                <th>REDUÇÃO PARA NOVOS BENEFICIÁRIOS </th>
                                <th>REDUÇÃO PARA ADVINDOS DA CONCORRÊNCIA </th>
                              </tr>
  
                              <tr>
                                <td>A </td>
                                <td>Consultas Médicas </td>
                                <td>Todas as especialidades reconhecidas pelo Conselho Federal de Medicina – CFM, exceto psicologia e psicoterapia </td>
                                <td>30 dias </td>
                                <td>24 horas* </td>
                                <td>24 horas* </td>
                              </tr>
  
                              <tr>
                                <td>B </td>
                                <td>Exames simples </td>
                                <td>Raios X simples, exames de análises Clínicas simples decorrentes de consultas médicas, exceto aqueles especificados
                                e/ou pertinentes a futuras atualizações do Rol de Procedimentos da Agência Nacional de Saúde Suplementar – ANS </td>
                                <td>30 dias </td>
                                <td>24 horas* </td>
                                <td>24 horas* </td>
                              </tr>
  
                              <tr>
                                <td>C </td>
                                <td>Terapias </td>
                                <td>Fisioterapia, sessões com nutricionista, sessões com fonoaudiólogo, sessões com terapeuta ocupacional, acupuntura e psicoterapia </td>
                                <td>180 dias </td>
                                <td>24 horas* </td>
                                <td>24 horas* </td>
                              </tr>
  
                              <tr>
                                <td>D </td>
                                <td>Exames Complementares </td>
                                <td>Ultrassonografia, mamografia, exames endoscópicos, exames radiológicos contrastados, exames anatomia patológica e cito patológica, eco
                                cardiograma uni e bidimensional, eletromiografia, fonocardiograma, prova ergométrica, exames de medicina nuclear, laparoscopia e prova
                                de função respiratória, tomografia computadorizada e ressonância magnética. </td>
                                <td>180 dias </td>
                                <td>90 dias* </td>
                                <td>60 dias* </td>
                              </tr>
  
                              <tr>
                                <td>E </td>
                                <td>Cirurgia e Internações </td>
                                <td>Todos procedimentos clínicos e cirúrgicos.</td>
                                <td>180 dias </td>
                                <td>180 dias* </td>
                                <td>120 dias* </td>
                              </tr>
  
                              <tr>
                                <td>F </td>
                                <td>Parto </td>
                                <td>Parto a termo</td>
                                <td>300 dias </td>
                                <td>300 dias* </td>
                                <td>300 dias* </td>
                              </tr>
                            </table>';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais4);
  
      $condicoes_gerais5 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            <b>DAS DOENÇAS E LESÕES PREEXISTENTES</b><br><br>
  
                            <b>13</b> Doença ou lesão preexistente é aquela em que o beneficiário saiba ser ou ter sido portador no momento
                            da contratação do plano de saúde.<br><br>
  
                            <b>13.1</b> Cobertura Parcial Temporária (CPT) é a suspensão, por um período ininterrupto de até 24 meses,
                            a partir da data da contratação ou adesão ao plano privado de assistência à saúde, da cobertura de
                            Procedimentos de Alta Complexidade (PAC), leitos de alta tecnologia e procedimentos cirúrgicos, desde
                            que relacionados exclusivamente às doenças e lesões preexistentes.<br><br>
  
                            <b>13.2</b> O beneficiário deverá preencher, no momento da contratação, a Declaração de Saúde conforme
                            disposto na Resolução Normativa nº 162 de 17 de outubro de 2007 da ANS.<br><br>
  
                            <b>14</b> Sendo constatada a existência de lesão ou doença preexistente que possa gerar necessidade de eventos
                            cirúrgicos, uso de leitos de alta tecnologia ou procedimentos de alta complexidade, o(s) beneficiário(s)
                            deverá(rão) cumprir a Cobertura Parcial Temporária (CPT) cujo prazo será de no máximo 24 (vinte e
                            quatro) meses a contar da vigência do beneficiário. Findado o prazo, a cobertura do plano passará a ser
                            integral, não cabendo qualquer tipo de agravo por doença ou lesão preexistente.<br><br>
  
                            <b>15</b> Será considerado como comportamento fraudulento a omissão de doença ou lesão preexistente de
                            conhecimento prévio do beneficiário.<br><br>
  
                            <b>15.1</b> Alegada a existência de doença ou lesão preexistente não declarada pelo beneficiário no preenchimento
                            da Declaração de Saúde, o beneficiário será imediatamente comunicado pela OPERADORA. Caso o
                            beneficiário não concorde com a alegação, a OPERADORA encaminhara a documentação pertinente à
                            Agência Nacional de Saúde Suplementar/ANS, e esta abrirá processo administrativo para investigação.<br><br>
  
                            <b>15.2</b> Cumpre esclarecer que durante o período em que a ANS estiver analisando o referido processo
                            investigatório, a OPERADORA poderá realizar o procedimento pretendido normalmente. Entretanto, se
                            ao término do processo investigatório for constatada a omissão do beneficiário em relação as doenças ou
                            lesões preexistentes, este deverá ressarcir, integralmente, todas as despesas decorrentes do procedimento
                            realizado à OPERADORA.<br><br>
  
                            <b>DA URGÊNCIA E DA EMERGÊNCIA</b><br><br>
  
                            <b>16</b> Atendimento de Urgência e Emergência<br>
                            Para efeitos desta cobertura, entende-se como atendimento de emergência aquele que implica no risco
                            imediato de vida ou de lesões irreparáveis para o paciente, caracterizado em declaração do médico
                            assistente. Como atendimento de urgência entende-se aquele resultante de acidente pessoal ou de
                            complicações no período gestacional.<br><br>
  
                            <b>16.1</b> Para os casos de urgência e emergência a OPERADORA garantirá assistência médica no sentido da
                            preservação da vida, órgãos e funções.<br><br>
  
                            <b>16.2</b> Quando o atendimento de urgência e emergência for efetuado no decorrer dos períodos de carência
                            será garantida cobertura e medicação limitadas até as primeiras 12 (doze) horas. Quando necessária, para
                            a continuidade de internação do atendimento de urgência e emergência, após o prazo das 12 (doze)
                            horas, a cobertura cessará, sendo do beneficiário a responsabilidade financeira, não cabendo ônus à
                            OPERADORA. A remoção do paciente será realizada pela OPERADORA para uma unidade do Serviço
                            Único de Saúde (SUS) que possua os recursos necessários para garantir a continuidade do atendimento.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais5);
  
      $condicoes_gerais6 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            <b>DA EXECUÇÃO DOS SERVIÇOS</b><br><br>
  
                            <b>17</b> Área de Abrangência e Locais de Atendimento
                            O referido plano possui abrangência geográfica nos seguintes municípios do Estado do Rio de Janeiro:
                            Duque de Caxias, Itaguaí, Rio de Janeiro e Seropédica.<br><br>
  
                            <b>18</b> É obrigatória a apresentação da Cédula de Identificação com foto para usufruir dos atendimentos e
                            recursos deste Contrato.<br><br>
  
                            <b>19</b> A rede credenciada de prestadores coberta pelo plano de saúde deverá ser consultada através do site da
                            OPERADORA (https://www.cemeru.com). Fica estabelecido que o(s) beneficiário(s) e seus dependentes
                            utilizarão o plano na rede credenciada mediante apresentação da numeração de matrícula do plano e/ou
                            a carteira provisória expedida pela ADMINISTRADORA através dos canais de atendimento e/ou o cartão
                            definitivo (não obrigatório).<br><br>
  
                            <b>20</b> A OPERADORA se reserva, outrossim, o direito de modificar, extinguir ou realizar novos convênios de
                            credenciamento de profissionais, clínicas e pronto socorros, mantendo sempre o seu alto padrão técnico.<br><br>
  
                            <b>21</b> Na hipótese da substituição de estabelecimento hospitalar por outro equivalente, a OPERADORA
                            comunicará a Agência Nacional de Saúde Suplementar/ANS no prazo de 30 (trinta) dias de antecedência,
                            garantindo assim a continuidade da internação.<br><br>
  
                            <b>DA FORMAÇÃO DO PREÇO E DA MENSALIDADE</b><br><br>
  
                            <b>22</b> Após o fechamento do efetivo mensal, a ADMINISTRADORA enviará fatura correspondente aos
                            atendimentos/mensalidades realizados no mês, sendo de responsabilidade da EMPRESA CONTRATANTE
                            o pagamento da mesma na totalidade de beneficiários inscritos, ressalvadas as hipóteses previstas nos
                            Art. 30 e 31 da Lei nº 9.656/98.<br><br>
  
                            <b>23</b> A EMPRESA CONTRATANTE efetuará a quitação mensal, na data de vencimento escolhida no ato
                            da assinatura deste Contrato, pelo número de beneficiários vinculados a empresa, sendo os valores
                            determinados de acordo com a faixa etária e produto escolhido por cada usuário.<br><br>
  
                            <b>23.1</b> Será responsabilidade da EMPRESA CONTRATANTE avisar à ADMINISTRADORA o não recebimento
                            do boleto até 2 (dois) dias úteis antes do seu vencimento. Após a data de vencimento da fatura incidirão
                            multa de 2% sobre o valor do débito em atraso e juros de mora de 1,0% ao mês, estando a ADMINISTRADORA
                            autorizada a realizar cobrança, caso haja pendência financeira, através de SMS, cartas, e-mails ou qualquer
                            outro meio de comunicação legal.<br><br>
  
                            <b>23.2</b> Será cobrada a Taxa de Reemissão de boleto caso a solicitação da segunda via seja feita após a data
                            do vencimento original da mensalidade, e seu valor será de R$ 3,00 (três reais), podendo ser reajustado
                            conforme a base tarifária do banco emissor. Todos os comunicados que forem entregues com a fatura
                            serão considerados notificações extrajudiciais.<br><br>
  
                            <b>23.3</b> Caso não ocorra o pagamento em seu vencimento, a ADMINISTRADORA se reserva ao direito
                            de proceder a inclusão do número do CNPJ da EMPRESA CONTRATANTE nos cadastros dos órgãos
                            restritivos de crédito, caso haja atrasos superiores a 30 (trinta) dias, bem como de seu representante
                            legal, o qual obriga-se a observar e cumprir os prazos e condições de pagamento estipulados neste
                            Contrato, como também respeitar as normas e regulamentos do benefício, respondendo civil e
                            criminalmente por quaisquer danos morais e materiais eventualmente causados por si e pela utilização
                            indevida do plano de saúde.<br><br>
  
                            <b>24</b> Não haverá distinção quanto ao valor da prestação entre os beneficiários que vierem a ser incluídos no
                            Contrato e aqueles a este já vinculado.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais6);
  
      $condicoes_gerais7 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            <b>25</b> Todos os pagamentos mensais serão efetuados pela EMPRESA CONTRATANTE à ADMINISTRADORA
                            através de boleto bancário nos prazos de cobrança e na forma estabelecida nos documentos emitidos
                            pela ADMINISTRADORA, não realizando a mesma, em hipótese alguma, cobrança domiciliar. Para
                            qualquer outro meio de pagamento, o mesmo somente poderá ser efetuado com prévia autorização da
                            ADMINISTRADORA.<br><br>
  
                            <b>26</b> A EMPRESA CONTRATANTE reconhece que os valores estabelecidos neste Contrato são líquidos e
                            certos, legitimando a emissão de faturamento mensal em conformidade com esta Cláusula e procedimento
                            executivo nos casos de inadimplência com a inclusão, então, dos juros legais e das despesas processuais,
                            advocatícias e demais cominações legais.<br><br>
  
                            <b>DO FATOR MODERADOR</b><br><br>
  
                            <b>27</b> O produto contratado não possuí cobrança de franquia ou coparticipação.
  
                            <b>DO REAJUSTE</b><br><br>
  
                            <b>28</b> A atualização dos valores dos custos mensais será efetuada anualmente, no mês de aniversário do
                            Contrato entre a ADMINISTRADORA e a OPERADORA, e ocorrerá no mês de AGOSTO de cada ano,
                            independente da data da adesão ao Contrato, pela variação do IPCA, que será apurado no período de 12
                            meses consecutivos, sendo aplicada a todos os usuários ativos no Contrato, independentemente da idade.<br><br>
  
                            <b>28.1</b> O percentual reajustado será informado à ANS até 30 (trinta) dias após a data da aplicação.<br><br>
  
                            <b>29</b> Independentemente da data de inclusão dos usuários, os valores de suas contraprestações sofrerão o
                            primeiro reajuste integral na data de aniversário (vigência) do Contrato ou quando em razão de mudança
                            de faixa etária, migração e adaptação do Contrato à Lei nº 9.656/98;<br><br>
  
                            <b>30</b> Na hipótese de se constatar a necessidade de aplicação do reajuste por sinistralidade, este será negociado
                            de comum acordo entre a ADMINISTRADORA e a OPERADORA, sendo que o nível de sinistralidade da
                            carteira terá por base a proporção entre as despesas assistenciais e as receitas diretas do plano, apuradas
                            no período de 12 (doze) meses consecutivos, anteriores à data base de aniversário considerada como o
                            mês de assinatura do contrato entre a EMPRESA CONTRATANTE e a OPERADORA.<br><br>
  
                            <b>30.1</b> Fica estabelecido para este Contrato o ponto de equilíbrio a ser considerado para eventual sinistralidade
                            em 65% (sessenta e cinco por cento).<br><br>
  
                            <b>31</b> Nos casos de aplicação de reajuste por sinistralidade, o mesmo será procedido de forma complementar
                            ao especificado no Item 30 e nas mesmas datas.<br><br>
  
                            <b>32</b> Será calculado valor único de percentual de reajuste para o agrupamento ao qual está agregado o Contrato.<br><br>
  
                            <b>DAS FAIXAS ETÁRIAS<br><br></b>
  
                            <b>33</b> Variação do preço em razão da faixa etária
                            Havendo alteração de faixa etária de beneficiário inscrito no presente Contrato, a contraprestação
                            pecuniária será reajustada no mês subsequente ao da ocorrência, de acordo com os valores da tabela
                            abaixo, que se acrescentarão sobre o valor da última contraprestação pecuniária, observadas a seguintes
                            condições, conforme determina o Art. 3º, Incisos I e II da RN nº 63/03.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais7);
  
      $tabela_cemeru1 = '<table border="1" cellspacing="0" style="width: 100%; text-align: center; margin-top: 0px;">
  
                          <tr>
                            <th> FAIXAS ETÁRIAS </th>
                            <th> OURO-PME </th>
                            <th> FIT-PME </th>
                          </tr>
  
                          <tr>
                            <th> 0 a 18 </th>
                            <th> 0% </th>
                            <th> 0% </th>
                          </tr>
  
                          <tr>
                            <th> 19 a 23 </th>
                            <th> 15,00% </th>
                            <th> 28,90% </th>
                          </tr>
  
                          <tr>
                            <th> 24 a 28 </th>
                            <th> 9,00% </th>
                            <th> 19,10% </th>
                          </tr>
  
                          <tr>
                            <th> 29 a 33 </th>
                            <th> 7,70% </th>
                            <th> 2,30% </th>
                          </tr>
  
                          <tr>
                            <th> 34 a 38 </th>
                            <th> 9,00% </th>
                            <th> 5,00%</th>
                          </tr>
  
                          </table>';
      $tabela_cemeru2 = '<table border="1" cellspacing="0" style="width: 100%; text-align: center; margin-top: 0px;">
  
                          <tr>
                            <th> FAIXAS ETÁRIAS </th>
                            <th> OURO-PME </th>
                            <th> FIT-PME </th>
                          </tr>
  
                          <tr>
                            <th> 39 a 43 </th>
                            <th> 15,60% </th>
                            <th> 12,80% </th>
                          </tr>
  
                          <tr>
                            <th> 44 a 48 </th>
                            <th> 38,20% </th>
                            <th> 30,80% </th>
                          </tr>
  
                          <tr>
                            <th> 49 a 53 </th>
                            <th> 4,30% </th>
                            <th> 13,50% </th>
                          </tr>
  
                          <tr>
                            <th> 54 a 58 </th>
                            <th> 35,40% </th>
                            <th> 40,20% </th>
                          </tr>
  
                          <tr>
                            <th> 59 ou mais </th>
                            <th> 66,40% </th>
                            <th> 52,90%</th>
                          </tr>
  
                          </table>';
  
      $condicoes_gerais8 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            <table border="0" cellspacing="0" style="width: 100%; text-align: center; margin-top: 0px;">
                              <tr>
                                <td> '.$tabela_cemeru1.' </td>
                                <td> '.$tabela_cemeru2.' </td>
                              </tr>
                            </table><br><br>
  
                            <b>DAS REGRAS PARA INSTRUMENTOS JURÍDICOS DE PLANOS COLETIVOS POR ADESÃO</b><br><br>
  
                            <b>Termo de Permanência</b><br>
                            <b>34</b> O beneficiário titular que for demitido ou exonerado sem justa causa, decorrente da sua relação de
                            trabalho com a EMPRESA CONTRATANTE, terá o direito de formalizar Termo de Permanência conforme
                            as regras dos Art. 30 e 31 da Lei nº 9.656/98 e RN nº 279 da ANS.<br><br>
  
                            <b>35</b> A OPERADORA assegura ao beneficiário titular e seus dependentes vinculados já inscritos o direito de
                            manter sua condição de beneficiário no plano de saúde, nas mesmas condições de cobertura assistencial
                            de que gozava quando da vigência do Contrato de Trabalho quando este for desligado ou exonerar-se da
                            empresa, sem justa causa, através da formalização do Termo de Permanência.<br><br>
  
                            <b>36 Pagamento</b><br>
                            O beneficiário que realizar o Termo de Permanência assumirá o pagamento integral diretamente à
                            OPERADORA que lhe fornecerá as devidas instruções na forma do Art 30 da Lei nº 9.656/98 c/c RN nº
                            179/2011 da ANS.<br><br>
  
                            <b>37</b> Requisitos para realização do Termo de Permanência
                            O termo de permanência só será permitido quando cumprido os seguintes requisitos legais:
                            a) o ex-empregado formalizar o pedido de realização do Termo de Permanência no prazo máximo
                            de 30 (trinta) dias a contar da assinatura do Formulário de Exclusão formalizado junto à EMPRESA
                            CONTRATANTE, que será entregue no ato do comunicado de rescisão do Contrato de Trabalho.
                            b) contribuição do beneficiário titular pelo plano de saúde, através de desconto em Folha de Pagamento, no
                            qual comprove que este contribuiu total ou parcialmente pelo plano em decorrência do vínculo empregatício,
                            com exceção dos valores realizados à contribuição de dependentes, agregados e coparticipação.
                            c) assuma o pagamento integral do plano, conforme a tabela de valores e suas atualizações, estabelecida
                            no contrato principal firmado com a EMPRESA CONTRATANTE.<br><br>
  
                            <b>38 Período de Manutenção<br></b>
                            O período de manutenção do beneficiário no Termo de Permanência será de 1/3 (um terço) do tempo de
                            permanência no seu plano anterior, com um mínimo assegurado de 06 (seis) meses e um máximo de 24
                            (vinte e quatro) meses.<br><br>
  
                            <b>39 Extensão ao Grupo Familiar<br></b>
                            O Termo de Permanência poderá ser celebrado individualmente ou estendido a todo grupo familiar do
                            beneficiário, quando inscritos durante a vigência do Contrato de Trabalho.<br><br>
  
                            <b>39.1</b> É permitida a inclusão junto ao Termo de Permanência somente para novo conjuge e filhos do exempregado
                            demitido, exonerado ou aposentado.
  
                            ';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais8);
  
      $condicoes_gerais9 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            <b>40</b> Condições de perda do Termo de Permanência<br>
                            a) quando do término da contagem de prazo de permanência estipulado no momento da assinatura do
                            termo;<br>
                            b) pela admissão do beneficiário em novo emprego que lhe possibilite ingresso em novo plano; ou<br>
                            c) cancelamento do plano coletivo empresarial ao qual o beneficiário demitido encontrava-se vinculado.<br><br>
  
                            <b>41</b> Morte do Titular<br>
                            Em caso de morte do titular, o direito de permanência é assegurado aos dependentes cobertos pelo
                            plano privado coletivo de assistência à saúde, desde que assumam as mensalidades correspondentes aos
                            dependentes que optarem por permanecerem com o plano.<br><br>
  
                            <b>42 Aposentados</b><br>
                            Ao aposentado que contribuiu comprovadamente para o plano contratado decorrente de vínculo
                            empregatício, pelo prazo mínimo de 10 (dez) anos, a OPERADORA assegura ao beneficiário titular e seus
                            dependentes vinculados, o direito de manutenção como beneficiário no plano de saúde nas mesmas
                            condições de cobertura assistencial de que gozava quando da vigência do Contrato de Trabalho, desde
                            que assuma junto à OPERADORA o pagamento integral das mensalidades, na forma do Art. 31 da Lei nº
                            9.656/98.<br><br>
  
                            <b>42.1</b> Ao aposentado que contribuiu comprovadamente para o plano, por período inferior a 10 (dez) anos,
                            é assegurado o direito de realizar o Termo de Permanência à razão de 1 (um) ano para cada ano de
                            contribuição, desde que assuma o pagamento integral do plano.<br><br>
  
                            <b>42.2</b> O aposentado deve optar pela manutenção do benefício no prazo máximo de 30 dias a contar da
                            assinatura do Formulário de Exclusão formalizado junto à EMPRESA CONTRATANTE que será entregue
                            no ato do comunicado de rescisão contratual;<br><br>
  
                            <b>43</b> Aposentado que permanecer trabalhando:<br>
                            O aposentado que continuar trabalhando na mesma empresa e venha a se desligar desta, é garantido o
                            direito de manter sua condição de beneficiário através do Termo de Permanência, devendo este optar pela
                            manutenção no prazo máximo de 30 (trinta) dias a contar da data de seu desligamento junto à EMPRESA
                            CONTRATANTE e da assinatura do Formulário de Exclusão;<br><br>
  
                            <b>43.1</b> O Termo de Permanência também poderá ser estendido aos dependentes já inscritos do empregado
                            aposentado que continuou trabalhando na mesma empresa e que veio a falecer antes de se desligar,
                            cabendo a estes optar pela manutenção no prazo máximo de 30 (trinta) dias a contar do óbito e da
                            assinatura do Formulário de Exclusão.<br><br>
  
                            <b>44</b> Cancelamento do Plano Empresarial:<br>
                            Em caso de cancelamento dos planos coletivos empresariais terão os beneficiários a garantia de continuar
                            com o plano de saúde na modalidade coletivo por adesão ou empresarial, sem a necessidade do
                            cumprimento de novos prazos de carências, conforme Súmula Normativa 21 da ANS, desde que comunique
                            a OPERADORA no prazo máximo de 30 (trinta) após o seu término.<br><br>
  
                            <b>45</b> Formas de contribuições permitidas:<br>
                            Nos planos custeados integralmente pela EMPRESA CONTRATANTE, quando o titular não participar
                            financeiramente do plano durante o período que mantiver o vínculo empregatício, este não terá direito
                            ao Termo de Permanência. Não é considerada contribuição a coparticipação do beneficiário, única e
                            exclusivamente em procedimentos, como fator de moderação na utilização dos serviços de assistência
                            médica e/ou hospitalar.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais9);
  
      $condicoes_gerais10 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            <b>46</b> O direito assegurado ao beneficiário titular, demitido ou aposentado, não exclui vantagens obtidas
                            pelos empregados decorrentes de negociações coletivas de trabalho.<br><br>
  
                            <b>DAS CONDIÇÕES DE PERDA DA QUALIDADE DE BENEFICIÁRIO</b><br><br>
  
                            <b>47</b> Compete à EMPRESA CONTRATANTE, na vigência deste Contrato, comunicar imediatamente as
                            ocorrências de demissões realizadas no período, bem como o recolhimento e a devolução das respectivas
                            Cédulas de Identificação, sendo que caberá somente à EMPRESA CONTRATANTE solicitar a exclusão dos
                            beneficiários.<br><br>
  
                            <b>47.1</b> As solicitações de exclusões devem conter a assinatura e o carimbo do representante legal da
                            EMPRESA CONTRATANTE;<br><br>
  
                            <b>47.2</b> As exclusões serão realizadas após 05 (cinco) dias do recebimento pela OPERADORA;<br><br>
  
                            <b>47.3</b> A EMPRESA CONTRATANTE será responsável pelos atendimentos prestados aos usuários demitidos
                            ou excluídos cujos nomes não tenham sido comunicados a OPERADORA, em tempo hábil, cabendo-lhe
                            indenizar os seus custos;<br><br>
  
                            <b>47.4</b> Na falta de comunicação em tempo oportuno, da inclusão ou da exclusão de beneficiários, a fatura
                            se baseará nos dados disponíveis, sendo os eventuais acertos realizados na fatura subsequente.<br><br>
  
                            <b>48</b> Cancelamento a pedido – RN 412
                            O benecifiário que desejar realizar o cancelamento de seu plano de saúde, deverá solicitar a exclusão junto
                            a empresa CONTRATANTE.<br><br>
  
                            <b>48.1</b> A empresa CONTRATANTE deverá enviar a solicitação de cancelamento a administradora através
                            do e-mail rempresarial@grupocontem.com.br, encaminhando a solicitação por escrito pelo responsável
                            pela empresa CONTRATANTE.<br><br>
  
                            <b>48.2</b> Caso o beneficiário solicite a portabilidade de seu plano para outra OPERADORA, ao concluir a
                            portabilidade, o beneficiário deverá solicitar o cancelamento do seu vínculo com a ADMINISTRADORA ou
                            OPERADORA de origem no prazo de 5 (cinco) dias a partir da data do início da vigência do seu vínculo
                            com o plano de destino.<br><br>
  
                            <b>49</b> A OPERADORA só poderá cancelar a assistência à saúde dos beneficiários, sem a anuência da EMPRESA
                            CONTRATANTE, nos seguintes casos:<br><br>
                            a) fraude comprovada mediante notificação formal ao beneficiário;<br>
                            b) perda do vínculo do titular com a EMPRESA CONTRATANTE ou de dependência, ressalvado o disposto
                            nos Art. 30 e 31 da Lei nº 9.656/98; ou<br>
                            c) agressão verbal ou física aos colaboradores da OPERADORA.<br><br>
  
                            <b>49.1</b> Para todos esses casos haverá comunicação formal da decisão à EMPRESA CONTRATANTE pela
                            OPERADORA.<br><br>
  
                            <b>DA RESCISÃO</b><br><br>
  
                            <b>50</b> O presente Contrato poderá ser rescindido nas hipóteses de fraude ou não pagamento da taxa mensal,
                            por período superior a 30 (trinta) dias consecutivo ou não, a cada 12 (doze) meses de vigência do Contrato,
                            cabendo à ADMINISTRADORA notificar a EMPRESA CONTRATANTE.';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais10);
  
      $condicoes_gerais11 = '<br><br><br><br><div style="text-align: justify; font-size: 13px;">
  
                            <b>51</b> A omissão de informações ou fornecimento de informações incorretas ou inverídicas pela EMPRESA
                            CONTRATANTE para auferir vantagens próprias ou para seus usuários é reconhecida como violação ao
                            Contrato, permitindo à ADMINISTRADORA e à OPERADORA realizar a rescisão do Contrato por fraude.<br><br>
  
                            <b>52</b> Caso não ocorra a quitação da mensalidade em até 05 (cinco) dias a contar da data do vencimento
                            original do boleto bancário, poderá ocorrer a suspensão do(s) beneficiários(as) e a utilização somente
                            será reestabelecida a partir da quitação integral do(s) valor(es) pendente(s) acrescido( s) dos encargos
                            supracitados.<br><br>
  
                            <b>52.1</b> O restabelecimento do serviço em caso de suspensão caso não ocorra a quitação da mensalidade
                            em até 05 (cinco) dias a contar da data do vencimento original, dependerá da comprovação da baixa
                            bancária.<br><br>
  
                            <b>DAS DISPOSIÇÕES GERAIS</b><br><br>
  
                            <b>53</b> Somente será possível postular nova adesão pela EMPRESA CONTRATANTE, mediante: (I) aceitação
                            pela ADMINISTRADORA, (II) quitação de eventuais débitos anteriores junto à ADMINISTRADORA,
                            mesmo que seja de Contrato de outra OPERADORA, e (III) cumprimento de novos prazos de carência,
                            independentemente do período anterior em que permaneceu no Contrato Coletivo.<br><br>
  
                            <b>54</b> Autorizo, expressamente, ressalvada as formas regulamentares de notificação, receber através de
                            e-mail, SMS e WhatsApp, notificações de cancelamento por inadimplência ao atingir 30 (trinta) dias em
                            atraso no pagamento, rescisão de contrato, inadimplência, reajuste anual, aviso de cobrança, migração e
                            demais avisos.<br><br>
  
                            <b>55</b> O Kit de Implantação, composto da Carteira do Plano e da Carta de Boas Vindas, será enviado ao
                            endereço de correspondência em até 30 (trinta) dias da data de vigência do Contrato através dos Correios
                            ou por empresa terceirizada. Fica excluído qualquer tipo de reembolso.<br><br>
  
                            <b>56</b> Para solicitar a 2ª via da carteira do plano de saúde será cobrado o valor da Taxa de Reemissão de
                            carteira (R$ 35,00 (trinta e cinco reais) por carteira).<br><br>
  
                            <b>57</b> O foro para dirimir quaisquer questões oriundas do presente contrato será o do Rio de Janeiro/RJ,
                            excluindo qualquer outro.
  
                            <div style="padding: 5px; background-color: #d3d3d3; font-size: 14px; margin-top: 30px">
                            <b>Dados da Empresa Contratante </b></div>
                            <table border="1" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 0px;">
                              <tr>
                               <td> <b>CNPJ: </b>'.$dados['cnpj'].' </td>
                               <td> <b>Razão Social: </b>'.$dados['razao_social'].'</td>
                              </tr>
                            </table>
  
                            <table cellspacing="-1" style="width: 100%; font-size: 12px; margin-top: 50px;">
                              <tr>
                               <td><center> ________________________________________,____,____,________ </center></td>
                               <td><center> ______________________________________________________ </center></td>
                              </tr>
  
                              <tr>
                               <td> <center>Local e data</center> </td>
                               <td> <center>Assinatura do sócio ou representante legal da empresa</center> </td>
                              </tr>
                            </table>
  
                            <div style="text-align: center; margin-top: 30px; width: 100%;">
                              <img src="https://www.grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/cond_gerais/contato_contem.png">
                            </div>';
      $mpdf->AddPage();
      $mpdf->WriteHTML($condicoes_gerais11);
    }
  
    $mpdf->Output($arquivo, 'I');




?>