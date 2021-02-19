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

if (isset($_GET['proposta']))
    $proposta = $_GET['proposta'];
else
    $proposta = null;

if (isset($_GET['id']))
    $id_benefic = $_GET['id'];
else
    $id_benefic = null;

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

//$mpdf->WriteHTML($info_plano);
//$mpdf->WriteHTML($dados_empresa);


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

  //$mpdf->AddPage();
  //$mpdf->WriteHTML($declara_termos);

  $beneficiarios_dados = mysqli_query($conexao, "SELECT * FROM wp_beneficiario where proposta = '$proposta' and id='$id_benefic'");

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

  
    $mpdf->Output($arquivo, 'I');
?>