<!-- <button id="dd">__</button>
<div class="text-center" id="XXX">    
    <p class="lead" style="font-size: 20rem;"></p>
    <i class="fas fa-hand-middle-finger text-success" style="font-size: 30rem; display: none;";></i>
</div>
<?php

use app\models\telephone\TelephoneSOAP;

$this->registerJs(<<<JS
    function showText (target, message, index) {
        if (index < message.length) {
            $(target).append(message[index++]);
            setTimeout(() => {
                showText(target, message, index)
            }, 500);
        }
        else {
            $(target).hide();
            $('#XXX i').show();
        }
    }
    $('#dd').on('click', () => showText('#XXX p', 'Серега!', 0));
JS); ?> -->


<?php

    $model = new TelephoneSOAP();
    print_r('<pre>');
    print_r($model->getAllOrganizations());


    // $soap = new SoapClient('http://86000-app012:8055/WSDLServices.nsf/telephones?WSDL', [
    //     // 'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
    //     // 'soap_version' => SOAP_1_2,
    //     // 'exceptions' => 1,
    // ]);
    // var_dump($soap->GetAllBooks());
    //$g = new SimpleXMLElement($soap->G());
    // foreach ($soap->G()->item as $item) {
    //     // echo $item . "<br />";   
    //     echo var_dump($item) . "<br />";     
    // }

    // $org = $soap->getAllStructByOrganization('85D04BCB61C9CEBC4525884B003EAD43');
    // if (isset($org->orgName)) {
    //     print_r(count($org->childs));
    // }

    // if ($org instanceof stdClass) {
    //     $org = get_object_vars($org);
    // }
    
    // // print_r(get_object_vars($org));

    // array_walk($org, function(&$item, $key) {
    //     if ($item instanceof stdClass) {      
    //         exit('aa');     
    //         $item = get_object_vars($item);
    //     }
    // });

    // print_r('<pre>');
    // print_r($org);

    // $orgs = $soap->GetAllOrgs();
    // $orgs_array = get_object_vars($orgs)['item'] ?? null;
    // if (is_array($orgs_array) && $orgs_array) {
    //     echo '<table class="table table-bordered">';
    //     foreach ($orgs_array as $orgStd) {
    //         $org = get_object_vars($orgStd);
    //         echo '<tr>';
    //         echo '<td>' . $org['code'] . '</td>';
    //         echo '<td>' . $org['name'] . '</td>';
    //         echo '<td>' . $org['address'] . '</td>';
    //         echo '<td>' . $org['telephone'] . '</td>';
    //         echo '<td>' . $org['ruk'] . '</td>';
    //         echo '</tr>';
    //     }

    // }


    // print_r('<pre>');
    // print_r(get_object_vars($books));
    // foreach($books as $book) {
    //     // echo '***';
    //     // $b = get_object_vars($book);
    //     // echo "ID: " . $book['Id'] . '<br />';
    //     echo "ID: " . $book->ID . ", Author: " . $book->author . ", Title: " . $book->title . '<br />';
    // }
?>