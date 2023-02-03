<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CommandExample extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:example';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run api every specific time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$url = 'http://entercy.test/api/test/';
        $url = 'http://entercy.test/api/service_email_reminder';
        //$Bearer='eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiNzZkYzk5YmM4OWYyNTMxOTYyM2E0ZTBiYjBjNmMzYTkyNWFjOGFlY2U4ZDJmNWUxNzA1NWI0YzNiZDdiOGEzMTYxNjRjY2VhYmI4YjExYzgiLCJpYXQiOjE2Mzc3NDk1NTMsIm5iZiI6MTYzNzc0OTU1MywiZXhwIjoxNjU1MDI5NTUzLCJzdWIiOiIyIiwic2NvcGVzIjpbIioiXX0.a67KqbXOOlSrGwzaKypdE6vYPpYHNLtJc2G4kO43D4HFaeMCTYMKNC2zQA-AYOYhxp5ATsHCFL_grFxfUgArmJdotq6dxO9mcnx09W_LX5Qy_gqZ9a6mN9gdGxeYbol1ZPdXGKD7ZdhJvfQfo92WX0rN92HoW7MVBezv91q91h0xFFSUytvFwQavWGvoK7LpSEoYYpGs3YzofLEzBjMjP6gDWxdrQNm1wAO-9oNlGeKwzM-ocbua6enA4MLkdNdcfCLcAtoYk4r-fiJVE_t2uBrYzlGeo6wbG5At4l3tS6pZmgbmbfn6uVQoeyrQhj-iH0vlMOoKoH6_mjQuNL89nqZ83LGex5kwkelJgcWLtkTeanSy8WmGZIee4Eoc-xOPtkjD2rpc-uRT45DYhTZHbNuqAOgbS142yU8Achmpj7yXClrrMa0rb2jeTb2gqZ6Ucl2x-rsZFGOrvrLvJ_u8f89DlePO1RRBllop6u8wodVSNzsF6Pj-7e-c2tnNuSHNGY5nF6Ij3DlLJ0H-PBT_v14vQRNt4DG9wJEeDq_J9mFFevBKQoj5Roit9f6MZNsKiQI3tRk7h-XWq0xXF3zkwNl9aOnQAMSVGKitYxlWr1GsoIIUYlpCi482VdW43Pr3AKmJlJiRIyz8mmnZ3rDJ0tm8v3r3MqtLWtvBLjFivhY';
        //The data you want to send via POST
        /*$Headers = [
            'Authorization: Bearer '.$Bearer,
        ];*/

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,"name=first_example");
        curl_setopt($ch, CURLOPT_POSTFIELDS,"name=service_email_reminder");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close ($ch);
        //$this->info("The time is " . date("h:i:sa").$server_output);
    }
}
