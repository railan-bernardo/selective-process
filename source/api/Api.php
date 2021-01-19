<?php

namespace Source\api;

use Source\api\Email;
class Api
{

    private $data;
    private $code;
    private $init;
    private $result;
    private $query;
    private $out;
    private $object;

    public function trackBack()
    {
        if (isset($_GET)) {
            $this->code = $_GET['code'];

            if ($this->code) {
                $this->query = array('Objetos' => $this->code);
                $this->init = curl_init();
                curl_setopt($this->init, CURLOPT_URL,
                    "https://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm");
                curl_setopt($this->init, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($this->init, CURLOPT_POSTFIELDS, http_build_query($this->query));
                $this->data = curl_exec($this->init);
                curl_close($this->init);

                $this->out = explode("<table class=\"listEvent sro\">", $this->data);

                if (isset($this->out[1])) {
                    $this->data = explode("<table class=\"listEvent sro\">", $this->data);
                    $this->data = explode("</table>", $this->data[1]);
                    $this->data = str_replace("</td>", "", $this->data[0]);
                    $this->data = str_replace("</tr>", "", $this->data);
                    $this->data = str_replace("<strong>", "", $this->data);
                    $this->data = str_replace("</strong>", "", $this->data);
                    $this->data = str_replace("<tbody>", "", $this->data);
                    $this->data = str_replace("</tbody>", "", $this->data);
                    $this->data = str_replace("<label style=\"text-transform:capitalize;\">", "", $this->data);
                    $this->data = str_replace("</label>", "", $this->data);
                    $this->data = str_replace("&nbsp;", "", $this->data);
                    $this->data = str_replace("<td class=\"sroDtEvent\" valign=\"top\">", "", $this->data);
                    $this->data = explode("<tr>", $this->data);

                    $this->result = [];

                    if (isset($_GET)) {
                        foreach ($this->data as $tableData) {
                            $info = explode("<td class=\"sroLbEvent\">", $tableData);
                            $resultData = explode("<br />", $info[0]);

                            $day = trim($resultData[0]);
                            $hour = trim(@$resultData[1]);
                            $location = trim(@$resultData[2]);

                            $resultData = explode("<br />", @$info[1]);
                            $action = trim($resultData[0]);

                            $exAction = explode($action . "<br />", @info[1]);
                            $msg = strip_tags(trim(preg_replace('/\s\s+/', ' ', $action)));

                            if ("" != $day) {
                                $exploDate = explode('/', $day);
                                $day1 = $exploDate[2] . '-' . $exploDate[1] . '-' . $exploDate[0];
                                $day2 = date('Y-m-d');

                                $difference = strtotime($day2) - strtotime($day1);
                                $days = floor($difference / (60 * 60 * 24));

                                $change = "há {$days} dias";
                                $this->result[] = ["date" => $day, "hour" => $hour, "location" => $location, "action" => utf8_encode($action), "message" => utf8_encode($msg), "change" => $change];
                            }

                        }
                    }
                    $this->object = (object)$this->result;

                    $html = "";
                    $html .= "<section style='width: 100%;padding: 15px;'>";
                    $html .= "<div style='width: 80%;margin: 0 auto;'>";
                    $html .= "<section style='width: 100%;display: block;padding: 15px;'>";
                    $html .= "<header style='width: 100%;padding: 15px;'>";
                    $html .= " <h1 style='  font-size: 1.3em;font-weight: bold;color: #343333;'><small style='color: #ea2222;font-size: 1.5em;'>E-</small>Lastic Brasil</h1>";
                    $html .= "</header>";
                    $html .= "<section style=' width: 100%;padding: 8px 0;'>";
                    $html .= "<h3 style=' width: 100%;padding: 15px;font-size: .9em;font-weight: bold;border-radius: 4px;padding: 8px;background: #0D1440;color: #ffffff;'>Acompanhe o Status da entrega</h3>";
                    $html .= "<p style=' font-size: 1em;font-weight: lighter;color: #2a2a2a;padding: 5px 0;'>Você pode acompanhar o envio com o código de rastreio <a href='http://www.rastreio.com.br'>{$this->code}</a></p>";
                    $html .= "</section>";
                    foreach ($this->object as $status) {
                        $html .= "<div style='width: 100%;display: block;border-bottom: 1px dashed #343333;padding: 15px 0;'>";
                        $html .= "<p style='font-size: 1em;font-weight: lighter;color: #2a2a2a;padding: 0;'>{$status['date']}</p>";
                        $html .= "<p style='font-size: 1em;font-weight: lighter;color: #2a2a2a;padding: 0;'> {$status['hour']} {$status['message']}</p>";
                        $html .= "<p style='font-size: 1em;font-weight: lighter;color: #2a2a2a;padding: 0;'>{$status['location']}</p>";
                        $html .= "<p style='font-size: 1em;font-weight: lighter;color: #2a2a2a;padding: 0;'>{$status['change']}</p>";
                        $html .= "</div>";

                    }
                    $html .= "<div style='width: 100%;display: block;padding: 15px 0;'>";
                    $html .= "<h2 style='font-size: 1.1em;font-weight: bold;color: #2a2a2a;'>Dados do Envio</h2>";
                    $html .= "<strong style='font-size: 1em;font-weight: bold;display: block;padding-bottom: 7px;'>Railan Bernardo</strong>";
                    $html .= "<span style='font-size: 1em;font-weight: lighter;padding: 2px 0;color: #343333;display: block;'>Tel: (62)99134-6028</span>";
                    $html .= "<span style='font-size: 1em;font-weight: lighter;padding: 2px 0;color: #343333;display: block;'>Travessa:</span>";
                    $html .= "<span style='font-size: 1em;font-weight: lighter;padding: 2px 0;color: #343333;display: block;'>Renascer: Goianira / GO</span>";
                    $html .= "<br><br><br>";
                    $html .= "<small style=' font-size: 1em;font-weight: lighter;color: #343333;'>Falta pouco!</small>";
                    $html .= "<p style=' border-bottom: 1px dashed #343333; font-size: 1em;font-weight: lighter;color: #343333;'>Equipe da E-Lastic Brasil</p>";
                    $html .= "</div>";
                    $html .= "</section>";
                    $html .= "</div>";
                    $html .= "</section>";

                    $email = new Email();

                    $email->add(
                        "Exercicio Rasteio de Objeto em PHP",
                        $html,
                        'Railan Bernardo',//João Macedo
                        "railabernardo2016@gmail.com"//joao.macedo@elastic.fit
                    )->send();


                    if (!$email->error()) {
                        echo $html;
                    } else {
                        echo $email->error()->getMessage();
                    }


                } else {
                    $this->result = new \stdClass();
                    $this->result->erro = true;
                    $this->result->msg = "Objeto não encontrado ou aguardando postagem";
                    $html = "";
                    $html .= "<div style=' width: 100%;padding: 15px;font-size: .9em;font-weight: bold;border-radius: 4px;padding: 8px;background: #e2c53c;color: #ffffff;'>{$this->result->msg}</div>";
                    echo $html;
                }

            } else {
                $this->result = new \stdClass();
                $this->result->erro = true;
                $this->result->msg = "Por favor Digite um Código";
                $html = "";
                $html .= "<div style=' width: 100%;padding: 15px;font-size: .9em;font-weight: bold;border-radius: 4px;padding: 8px;background: #e2c53c;color: #ffffff;'>{$this->result->msg}</div>";
                echo $html;
            }


        }
    }

}