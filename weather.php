<?php

class Weather
{
    /**
     * Yahoo Weather API Endpoint
     * 
     * @var string
     */
    private $endpoint = 'https://query.yahooapis.com/v1/public/yql/';

    /**
     * Main method to get the weather.
     * 
     * @param  int $zipcode Zip Code
     * @return string
     */
    public function getWeather($zipcode) 
    {
        try {
            if (empty($zipcode) || !is_numeric($zipcode)) {
                throw new Exception("A valid Zip Code must be submitted.");
            }
            
            $uri  = '?q=select%20item%20from%20weather.forecast';
            $uri .= '%20where%20location%3D"' . $zipcode . '"&format=json';
            $response = $this->request($uri);
            $responseFormatted = $this->formatResponse($response);

            $result = json_encode($responseFormatted);
        } catch(Exception $e) {
            $result = json_encode(array('error' => $e->getMessage()));
        }

        return $result;
    }

    /**
     * Format the response returned from the API.
     * 
     * @param  object $response JSON decoded response from the API
     * @return array
     */
    private function formatResponse($response)
    {
        $item = $response->query->results->channel->item;

        $responseFormatted = array(
            'title' => $item->title,
            'description' => $item->description,
        );

        return $responseFormatted;
    }

    /**
     * Send the CURL request to the API.
     * 
     * @param  string $uri URI added to the endpoint
     * @return object
     */
    private function request($uri)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->endpoint . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
         
        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response);
    }
}

$zipcode = $_GET['zipcode'];
$weather = new Weather();
print_r($weather->getWeather($zipcode));