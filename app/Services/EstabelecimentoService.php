<?php

namespace App\Services;

use App\Models\Estabelecimento;
use Faker;

class EstabelecimentoService
{
    public static function tipos(){
        $tipos = [
            0 => 'Hospital',
            1 => "Pronto Socorro",
            2 => "Clínica",
            3 => "Outros"
        ];
    }
    public static function topHospitais(){
        $topHospitais = [
            "Hospital Israelita Albert Einstein",
            "Hospital Sírio Libanês",
            "Hospital Oswaldo Cruz",
            "Hospital das Clinicas da Universidade de São Paulo",
            "Hospital Moinhos de Vento",
            "São Luiz Unidade Morumbi",
            "São Luiz Unidade Anália Franco",
            "Hospital Mae de Deus",
            "IMIP",
            "Hospital Santa Paula",
            "BP – Beneficência Portuguesa de São Paulo",
            "Santa Casa de Misericórdia Passos",
            "Hospital Santa Catarina",
            "São Luiz Unidade Itaim",
            "São Luiz Unidade Copa D'or",
            "Centro Médico de Campinas",
            "Hospital Samaritano",
            "Hospital Ministro Costa Cavalcanti",
            "Santa Casa de Misericórdia Maceió",
            "Hospital do Coração",
            "Hospital 9 de Julho",
            "Hospital Márcio Cunha",
            "Vitoria Apart Hospital",
            "Hospital Mater dei Santo Agostinho",
            "Santa Casa de Misericórdia Porto Alegre",
            "Hospital Universitário da USP",
            "Hospital São Camilo Pompeia",
            "Hospital Quinta D'or",
            "Centro Hospitalar Unimed",
            "Hospital Monte Sinai",
            "Hospital Alvorada Moema",
            "Hospital de Clinicas de Porto Alegre",
            "Hospital de Ensino da UNIFESP",
            "Hospital Meridional",
            "Hospital Divina Providencia",
            "Hospital São Lucas",
            "Hospital Vila da Serra",
            "Hospital Paraná",
            "Hospital Edmundo Vasconcelos",
            "Hospital Samaritano",
            "Hospital Santa Rita de Cássia"
        ];
        return $topHospitais;
    }

    public static function imagens(int $key){
        $imagens = [
            "https://streetviewpixels-pa.googleapis.com/v1/thumbnail?panoid=ggR3hC1ObYvx3wyJTdPxAA&cb_client=search.gws-prod.gps&w=408&h=240&yaw=82.71394&pitch=0&thumbfov=100",
            "https://lh5.googleusercontent.com/p/AF1QipNSPHLtl8cAeg6FCqrD_r9Ag2zwJM4_6mlu0vZc=w408-h306-k-no",
            "https://lh5.googleusercontent.com/p/AF1QipOtm-DRytCOO-c5Rex6RYFeBZXo0RjwpFDcpfaC=w426-h240-k-no",
            "https://streetviewpixels-pa.googleapis.com/v1/thumbnail?panoid=dFJ-N5hN4r4u-KrcDftyWg&cb_client=search.gws-prod.gps&w=408&h=240&yaw=48.1558&pitch=0&thumbfov=100",
            "https://lh5.googleusercontent.com/p/AF1QipNu3SY7AJ_ZFOU3qv5M-jrZnI1AmiUk0mt567tX=w408-h544-k-no",
            "https://lh5.googleusercontent.com/p/AF1QipPlfmlrP2-3ouRc46X4ILJqTNejksZ8gnly0QrI=w408-h306-k-no",
            "https://lh5.googleusercontent.com/p/AF1QipNzsYifIuIyF_pqa8xrDLZMXDQLLewFiRZOIHY8=w408-h296-k-no",
            "https://streetviewpixels-pa.googleapis.com/v1/thumbnail?panoid=-4aFT6NbRMBIw7vjqiwxDQ&cb_client=search.gws-prod.gps&w=408&h=240&yaw=324.6779&pitch=0&thumbfov=100",
            "https://lh5.googleusercontent.com/p/AF1QipPPUEoRX438B0upNspdZ0GNhkhgQakQHgKiGzYx=w408-h544-k-no",
            "https://wp-midia-nova.bidu.com.br/uploads/2018/08/03153227/melhores-hospitais.jpg",
            "https://s2.glbimg.com/_eE8DcuLIOAnsxsZteSqeR4qjmk=/620x413/smart/e.glbimg.com/og/ed/f/original/2016/08/26/or360feature.jpg",
            "https://setorsaude.com.br/wp-content/uploads/2019/04/Johns-Hopkins-Hospital.jpg",
            "https://pt.bcdn.biz/Images/2017/9/19/a6d574b1-947f-4e49-a2c0-0b48cd832d28.jpg",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ6JEXJQMG0ROmZNSf5crdwBBdL2wZb6tcP5_abAHavTyRYMmILclwZb_oap5aKY-0xsR8&usqp=CAU",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR3En4q9hwFu7JJW4gk4E4mmgwKQxzMGQJ8b4vHkoYNBbQJTdhz7GO8L3w1qBZBKg4sPbw&usqp=CAU",
            "https://setorsaude.com.br/wp-content/uploads/2019/04/Mass_General_Hospital.jpg",
            "https://s2.glbimg.com/aeXpp1iHNbZZ1rcJuqUl3-AkgOw=/0x0:1242x950/1008x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_59edd422c0c84a879bd37670ae4f538a/internal_photos/bs/2020/T/U/WxguIlS0ybARojccvnMQ/3d9b5950-f8ef-4ab9-8d3c-f311ec4ad184.jpg",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR2c_o2n0Sxx_eYOYezZQ0n5sJZJzcDezB9BQ&usqp=CAU",
            "https://bsbnoticias.com.br/hf-conteudo/uploads/posts/2021/03/30171_438a84067b8f51c17c1464b02de0b749.jpg",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSfJ-ZPakj_SMju921sx4XCMr4vS6FKtCDJ_bopT_nKMUcdJukD7BGQ8H-4bUMX27wV7MA&usqp=CAU",
            "https://cdn1.mundodastribos.com/489958-HCor-S%C3%A3o-Paulo.jpg",
            "https://i.imgur.com/S8Wk2I0.jpg",
            "https://cdn.mundodastribos.com/2012/07/489958-Hospital-S%C3%A3o-Vicente-de-Paulo-Rio-de-Janeiro.jpg",
            "https://gehosp.com.br/wp-content/uploads/2019/10/s%C3%A3o-vicente-de-paulo.jpg",
            "https://www.philips.com.br/c-dam/b2bhc/br/articles/hiss-hsvp/Hospital-Sao-Vicente-de-Paulo.jpg",
            "https://veja.abril.com.br/wp-content/uploads/2020/02/unidade-morumbi.jpg",
            "https://setorsaude.com.br/wp-content/uploads/2020/05/Hospital-Albert-Einstein-cria-primeiro-teste-de-diagn%C3%B3stico-gen%C3%A9tico-do-mundo-capaz-de-processar-16-vezes-mais-amostras.jpg",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT2sDM9Hg3fi85JseZlT2-RDpkDo0HwOL-F45W1dDirhbNBS1rQatVc85YYYB2VGZH_WQQ&usqp=CAU",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSuh8nJ3nAjxWZ6P6qpfXk-JlBwdiX2XY3kpA&usqp=CAU",
            "https://setorsaude.com.br/wp-content/uploads/2015/04/Moinhos-de-Vento-e-M%C3%A3e-de-Deus-entre-os-melhores-hospitais-para-se-trabalhar.jpg",
            "https://www.hospitaldebase.com.br/files/noticia/3198/hb.jpg",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcThbJ04uUESotIUCsqwiAYbvGbAe_7ag2Vq7A&usqp=CAU",
            "https://i0.wp.com/jrs.digital/wp-content/uploads/2021/03/Hospital_da_Luz_-_Unidade_Avancada-scaled.jpg?fit=1024%2C683&ssl=1",
            "https://setorsaude.com.br/wp-content/uploads/2019/04/Charit%C3%A9.jpg",
            "https://setorsaude.com.br/antonioquinto/wp-content/blogs.dir/6/files/2016/08/hospitais-brasileiros.jpg",
            "https://i0.wp.com/top10mais.org/wp-content/uploads/2017/02/Cleveland-Clinic-entre-os-melhores-hospitais-clinicos-do-mundo.jpg?resize=600%2C330&ssl=1",
            "https://setorsaude.com.br/wp-content/uploads/2019/04/Toronto-General-Hospital.jpg",
            "https://setorsaude.com.br/wp-content/uploads/2018/10/Hospital-Tacchini-passa-a-integrar-grupo-seleto-de-hospitais-de-excel%C3%AAncia-do-Brasil.jpg",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRO7WPv5dbvV48HAZn2D4dzY3KvBucCECUfXA&usqp=CAU",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ2W0hLZFEyXqDBjHD799D8MsBJ2bycorEjRw&usqp=CAU",
            "https://lh6.googleusercontent.com/PtnwpiZTdlDR4xqMxQv9jxYsf1Tf1BsPJ9z8RHIkVYUjc63jamcGrhdezwZ5-ddlOxwvypmPXresR0eIlDHooGcNkdBpNEdC_GzcZdG8VCSJwBRp4yMF28telVv0zVeTN7P268YA"
        ];
        return $imagens[$key%count($imagens)];
    }


    public static function mock(){
        $faker = Faker\Factory::create("pt_BR");
        $topHospitais = self::topHospitais();
        foreach($topHospitais as $key => $hospital){
            $estabelecimento = new Estabelecimento();
            $estabelecimento->telefone = $faker->phoneNumber();
            $estabelecimento->email = $faker->email();
            $estabelecimento->cnpj = $faker->cnpj();
            $estabelecimento->nome = $hospital;
            $estabelecimento->imagem = self::imagens($key);
            $estabelecimento->site = $faker->url();
            $estabelecimento->tipo = str_contains($hospital, 'Hospital') ? 0 : rand(0, 3);//self::tipos();
            $estabelecimento->deleted_at = rand(1,100) < 2 ? now()->toDateString() : null;
            $estabelecimento->save();
        }
    }

    public static function getAll(bool $json = true){
        $result = Estabelecimento::query()->latest()->get();
        if($json){
            return response()->json($result->toArray());
        }else{
            return $result;
        }
    }
    
    public static function findJson(int $id){
        try {
            $result = Estabelecimento::query()->find($id);
            if($result){
                return response()->json($result->toArray());
            }else{
                return response()->json(["mensagem" => "Não encontrado"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["mensagem" => "Id inválido"], 400);
        }
    }
}
