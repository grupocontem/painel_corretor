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
    $mpdf->Output($arquivo, 'I');
?>