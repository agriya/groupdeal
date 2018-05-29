<?php
/**
 * Group Deal
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    GroupDeal
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
/**
 * Component para tratamento dos dados para envio ao PagSeguro
 * Este componente foi devenvolvido para trabalhar com o Cake 1.3
 *
 * @author Felipe Theodoro Gonçalves
 *
 */
class PagSeguroComponent extends Component
{
    var $timeout = 20; // Tempo em segundos para timeout na conexão com o retorno
    var $token = null; // Token gerado pelo site do pagseguro para recebimento altomatico de status da venda
    var $__dataPayment = array(); // Array Contendo o status da venda
    
    /**
     * Atributo com os dados padrões para enviu para pag seguro
     * @var Array
     */
    var $__init = array(
        'pagseguro' => array( // Array com informações pertinentes ao pagseguro
            'type' => 'CP', // Obrigatório passagem para pagseguro:tipo
            'reference' => null, // Obrigatório passagem para pagseguro:ref_transacao
            'freight_type' => 'EN', // Obrigatório passagem para pagseguro:tipo_frete
            'theme' => 1, // Opcional Este parametro aceita valores de 1 a 5, seu efeito é a troca dos botões padrões do pagseguro
            'currency' => 'BRL', // Obrigatório passagem para pagseguro:moeda,
            'extra' => 0
            // Um valor extra que você queira adicionar no valor total da venda, obs este valor pode ser negativo
            
        ) ,
        'definitions' => array( // Array com informações para manusei das informações
            'currency_type' => 'dolar', // Especifica qual o tipo de separador de decimais, suportados (dolar, real)
            'weight_type' => 'kg', // Especifica qual a medida utilizada para peso, suportados (kg, g)
            'encode' => 'utf-8'
            // Especifica o encode não implementado
            
        ) ,
        'customer' => array( // Array com informações do cliente, opcional para o pagseguro
            'cliente_nome' => null,
            'cliente_cep' => null,
            'cliente_end' => null,
            'cliente_num' => null,
            'cliente_compl' => null,
            'cliente_bairro' => null,
            'cliente_cidade' => null,
            'cliente_uf' => null,
            'cliente_pais' => null,
            'cliente_ddd ' => null,
            'cliente_tel' => null,
            'cliente_email' => null
        ) ,
        // Array especificando o cruzamento das informações de entrada para as de saída
        // O primeiro parametro é fixo sempre deve ser estes o segundo é setavel de acordo com as informações de entrada
        // exemplo:
        // format => (item_id => id, item_descr => description)
        'format' => array(
            'item_id' => 'item_id',
            'item_descr' => 'item_descr',
            'item_quant' => 'item_quant',
            'item_valor' => 'item_valor',
            'item_frete' => 'item_frete',
            'item_peso' => 'item_peso'
        )
    );
    /**
     * Atributo com os dados gerados apos o create
     * @var Array
     */
    var $__items = array();
    /**
     * Inicia as informações necessárias
     * É Obrigatorio entrar com pelo menos o email de cobrança, caso o mesmo não seja especificado um fatal error será gerado
     * É estremamente recomendado que seja forncecido o reference contendo um código único para a venda
     *
     * @param Array $data
     */
    function init($data) 
    {
        if (is_array($data)) {
            // Set the token
            if (isset($data['pagseguro']['token']) && !empty($data['pagseguro']['token'])) $this->token = $data['pagseguro']['token'];
            // Set the correct value of extra
            if (isset($data['pagseguro']['extra']) && !empty($data['pagseguro']['extra'])) $data['pagseguro']['extra'] = $this->__formatCurrency($data['pagseguro']['extra']);
            // Set definitions data
            if (isset($data['definitions'])) $this->__init['definitions'] = array_merge($this->__init['definitions'], $data['definitions']);
            // Set definitions data
            if (isset($data['format'])) $this->__init['format'] = array_merge($this->__init['format'], $data['format']);
            // Set customer data
            if (isset($data['customer'])) $this->__init['customer'] = array_merge($this->__init['customer'], $data['customer']);
            // Set definitions data of pagseguro
            if (isset($data['pagseguro'])) $this->__init['pagseguro'] = array_merge($this->__init['pagseguro'], $data['pagseguro']);
            // Validate required email of sale
            if (!isset($this->__init['pagseguro']['email']) || !$this->__validateEmail($this->__init['pagseguro']['email'])) trigger_error(__l('Email recovery is not properly informed') , E_USER_ERROR);
        } else if (!$this->__validateEmail($data)) trigger_error(__l('Email recovery is not properly informed') , E_USER_ERROR);
        else $this->init['pagseguro']['email'] = $data;
    }
    /**
     * Create the data to be sent to pagseguro
     * This Methodo already converted some data according to the specification of pagseguro and the data provided in the init.
     * This accepts both Methodo selling a single product for multiple instance:
     * 0 => 'ShoppingCart' => array (id. ..)
     * Or
     * 'ShoppingCart' => array (id. ..)
     * Note: This Methodo only stores the data for all the PayPal and more informed than the use of PayPal
     * Will be discarded
     * Not allowed to send more than 25 products for pagseguro
     * @ Param array $ products
     */
    function create($products = array()) 
    {
        if (is_array($products) && !empty($products)) {
            $i = 1;
            $e = 1;
            foreach($products as $item) {
                foreach($item as $key => $value) {
                    if (is_array($value)) {
                        foreach($value as $key2 => $vv) {
                            if (in_array($key2, $this->__init['format'])) $this->__items[$i][] = $this->__serialiseItem($key2, $vv, $i);
                        }
                        ++$i;
                    } else {
                        if (in_array($key, $this->__init['format'])) $this->__items[$e][] = $this->__serialiseItem($key, $value, $e);
                    }
                }
                $e++;
            }
        }
    }
    /**
     * Method to render the parameters or return generated
     * Note: This Methodo only stores the data for all the PayPal and more informed than the use of PayPal
     * Will be discarded
     */
    function render() 
    {
        return array(
            'init' => $this->__init,
            'data' => $this->__items
        );
    }
    /**
     * Methodo * checks whether the request is a post
     * @ Return bool
     */
    function isConfirmation() 
    {
        if (empty($_POST)) return false;
        else return true;
    }
    /**
     * Methodo Confirma o resultado da compra
     */
    function confirm() 
    {
        return $this->__confirm();
    }
    /**
     * Methodo privado para acesso exclusivo do pagseguro
     */
    function __confirm() 
    {
        $postdata = 'Comando=validar&Token=' . $this->token;
        foreach($_POST as $key => $value) {
            $val = $this->__clearstr($value);
            $postdata.= "&$key=$val";
        }
        return $this->__getConfirmation($postdata);
    }
    /**
     * Recebe e organiza as informações do PagSeguro
     *
     */
    function getDataPayment() 
    {
        $post = $_POST;
        $this->__dataPayment['pagseguro']['email'] = $post['VendedorEmail'];
        unset($post['VendedorEmail']);
        $this->__dataPayment['pagseguro']['transaction_id'] = $post['TransacaoID'];
        unset($post['TransacaoID']);
        $this->__dataPayment['pagseguro']['reference'] = $post['Referencia'];
        unset($post['Referencia']);
        $this->__dataPayment['pagseguro']['extras'] = $post['Extras'];
        unset($post['Extras']);
        $this->__dataPayment['pagseguro']['freight_type'] = $post['TipoFrete'];
        unset($post['TipoFrete']);
        $this->__dataPayment['pagseguro']['freight_price'] = $post['ValorFrete'];
        unset($post['ValorFrete']);
        $this->__dataPayment['pagseguro']['annotation'] = $post['Anotacao'];
        unset($post['Anotacao']);
        $this->__dataPayment['pagseguro']['data_transaction'] = $post['DataTransacao'];
        unset($post['DataTransacao']);
        $this->__dataPayment['pagseguro']['type_payment'] = $post['TipoPagamento'];
        unset($post['TipoPagamento']);
        $this->__dataPayment['pagseguro']['status_transaction'] = $post['StatusTransacao'];
        unset($post['StatusTransacao']);
        $this->__dataPayment['pagseguro']['cliente_nome'] = $post['CliNome'];
        unset($post['CliNome']);
        $this->__dataPayment['pagseguro']['cliente_email'] = $post['CliEmail'];
        unset($post['CliEmail']);
        $this->__dataPayment['pagseguro']['cliente_end'] = $post['CliEndereco'];
        unset($post['CliEndereco']);
        $this->__dataPayment['pagseguro']['cliente_num'] = $post['CliNumero'];
        unset($post['CliNumero']);
        $this->__dataPayment['pagseguro']['cliente_compl'] = $post['CliComplemento'];
        unset($post['CliComplemento']);
        $this->__dataPayment['pagseguro']['cliente_bairro'] = $post['CliBairro'];
        unset($post['CliBairro']);
        $this->__dataPayment['pagseguro']['cliente_cidade'] = $post['CliCidade'];
        unset($post['CliCidade']);
        $this->__dataPayment['pagseguro']['cliente_uf'] = $post['CliEstado'];
        unset($post['CliEstado']);
        $this->__dataPayment['pagseguro']['cliente_cep'] = $post['CliCEP'];
        unset($post['CliCEP']);
        $this->__dataPayment['pagseguro']['cliente_tel'] = $post['CliTelefone'];
        unset($post['CliTelefone']);
        $this->__dataPayment['pagseguro']['NumItens'] = $post['NumItens'];
        unset($post['NumItens']);
        $this->__dataPayment['pagseguro']['Parcelas'] = $post['Parcelas'];
        $i = 1;
        $count = (count($post) /2);
        while ($i <= $count) {
            if (!isset($post['ProdID_' . $i])) break;

            $this->__dataPayment['pagseguro'][$i][$this->__init['format']['item_id']] = $post['ProdID_' . $i];
            $this->__dataPayment['pagseguro'][$i][$this->__init['format']['item_descr']] = $post['ProdDescricao_' . $i];
            $this->__dataPayment['pagseguro'][$i][$this->__init['format']['item_valor']] = $post['ProdValor_' . $i];
            $this->__dataPayment['pagseguro'][$i][$this->__init['format']['item_quant']] = $post['ProdQuantidade_' . $i];
            $this->__dataPayment['pagseguro'][$i][$this->__init['format']['item_frete']] = $post['ProdFrete_' . $i];
            $this->__dataPayment['pagseguro'][$i]['extra'] = $post['ProdExtras_' . $i];
            $i++;
        }
        return $this->__dataPayment;
    }
    /**
     * Methodo responsavel por confirmar o pagamento
     * @param $data
     */
    function __getConfirmation($data) 
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://pagseguro.uol.com.br/pagseguro-ws/checkout/NPI.jhtml");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = trim(curl_exec($curl));
        curl_close($curl);
        return $result;
    }
    /**
     * Methodo limpar a url
     * @param $val
     */
    function __clearstr($val) 
    {
        if (!get_magic_quotes_gpc()) $val = addslashes($val);
        return $val;
    }
    /**
     * Este methodo organiza os dados de entrada conforma especificado
     * @param $label
     * @param $value
     * @param $i
     */
    function __serialiseItem($label, $value, $i) 
    {
        if ($this->__init['format']['item_id'] == $label) return array(
            'item_id_' . $i => $value
        );
        else if ($this->__init['format']['item_descr'] == $label) return array(
            'item_descr_' . $i => $value
        );
        else if ($this->__init['format']['item_quant'] == $label) return array(
            'item_quant_' . $i => $value
        );
        else if ($this->__init['format']['item_valor'] == $label) return array(
            'item_valor_' . $i => $this->__formatCurrency($value)
        );
        else if ($this->__init['format']['item_frete'] == $label) return array(
            'item_frete_' . $i => $this->__formatCurrency($value)
        );
        else if ($this->__init['format']['item_peso'] == $label) return array(
            'item_peso_' . $i => $this->__convertWeight($value)
        );
        else return null;
    }
    /**
     * Methodo para converter os dados de entrada de kg para g conforme especificação do pagseguro
     * @param $weight
     */
    function __convertWeight($weight) 
    {
        if ($this->__init['definitions']['weight_type'] === 'kg') if (preg_match('/(([0-9]+)\.([0-9]){1,2})/', $weight)) return $weight*1000;
        else if (preg_match('/(([0-9]+)\,([0-9]){1,2})/', $weight)) return str_replace(',', '.', $weight) *1000;
        else return $weight*1000;
        if ($this->__init['definitions']['weight_type'] === 'g') return $weight;
    }
    /**
     * Converter values in both U.S. dollars and actual format and sets the format specified by pagseguro
     * @param $value
     */
    function __formatCurrency($value) 
    {
        if ($this->__init['definitions']['currency_type'] === 'dolar') {
            if (preg_match('/(([0-9]+)\.([0-9]{1,2}))/', $value)) {
                $value.= 00;
                list($val, $decimal) = explode('.', $value);
                $decimal = substr($decimal, 0, 2);
                return $val . $decimal;
            } else return $value . '00';
        } else if ($this->__init['definitions']['currency_type'] === 'real') {
            if (preg_match('/(([0-9]+)\,([0-9]{1,2}))/', $value)) {
                $value.= 00;
                list($val, $decimal) = explode(',', $value);
                $decimal = substr($decimal, 0, 2);
                return $val . $decimal;
            } else return $value . '00';
        } else return $value;
    }
    /**
     * Validador do email de cobrança
     * este methodo necessita da lib Validation do Core
     * @param $email
     */
    function __validateEmail($email = null) 
    {
        App::import('Core', 'Validation');
        return Validation::email($email);
    }
}
?>