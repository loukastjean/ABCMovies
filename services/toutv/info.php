<?php

declare(strict_types=1);

$refresh_token = "eyJraWQiOiJTaC1XeFNhUUdLTUxLVnR0b2dOalg4UVZ5SWpXT0xDLTFlS2h6X0NndTg0IiwidmVyIjoiMS4wIiwiemlwIjoiRGVmbGF0ZSIsInNlciI6IjEuMCJ9.PiT6U1-dVNk5FMsqZP7ynZQt4r-K-sojJaUeIkqAjwK99bpEaGyW9yUewyhrYWtpyAlCSZOP0WsCSQQcBhF9ZQOXqBBxkowF6nSanm1wT5ars_rKAQWPgMaYHXneFfdDAdJJbaWPNSQgTMo-P8h6jWACCLSIOIyEqbOTAyiDFwLxSuUcSqiruYjYeMBdzOVKBCapKyrVX9H7iYq72Q4_x2ij2BUjzv66dBPTISxpfeI4ptuW-5ZVC3jsUSz3Yg5_mjlcpFQ7IwrYtoXjTLj8--9-o7wRpX2Ni_J569fNCqfI2Wnpjkph66UVSkzBywKWB0n_Wr9slUOHemGDbirUVw.PmGZByDlFy2XDotu.sZHdBhybNAjQCs5TFTarJL0_7nQ3Giw00fZtkNm2fF8lZgJ9fXQAl7KbcOzgkISAnEQD6a7a22p1aF4TvGs-6lbzH51manPUA6Qh6nTFb5NBWrekX9W5qez9EN42fLJkdQTOQ6g0-pNPGXoCZEI8x9A2--f-L7_F_qPGO44kl87yLt1Bba-mkIZBfozBP4P78Mo5Rgoqbep-aQFGjBkTnuic4fXe-vsP3cgE97-ebV-BXL9gBMwS-vHnYGimRDFhe6BI4GeAtCXx22ciYS6U_hdIicQno7Ep3a-Da8GdsCCN2CElUxpdnUzTtIJHk4W9flHDVfz8VXekTpVDdgiRVGSLFgPAZPorH1yl4I-L4f5xdVjVRFNtST1PzmkSPQIsGEsdyWOL6VKstM_qZj_v-81veAlBEGWra9t1eG_QkQ5ZncaOIsyhHe-fyVMxWPqYB4A6_c6liw-06DmsR1T4jmPr63IRaVo3-bpz7gmDnipl6yJ3FL3TZMkmp4iiAo1mmiOCStSLdEBTPPVyTKWtTYf9QVSbkBddo7m6N2YSjYXq_dvf76gBkrJZJ4Xvi3G2DFcKc4KmOTJE5KccEhgr9DUWo--SFZC9JyZtjTnpN8BSyCFmO8jQDU7faLNtdRIGyiKVpcFeYRkycf6Cc-pcRzWD_D-A0E5m_Tc0kKJbqB5xGvOf-4M0BNseX0DbBKDjTjAuTSf2AKSmYpe-64YR8ASUzw8Tuxap57tfrREzimGoIgL5_eJZNHVNZyC_B54KNOQrzPP4mDrz9fRJROPpyBq0uHWwdCS-foUZeyUsKl50t0Mn_Y1gE1AuKuw1DWamz5S_Xi9OCRoNaNDm7bzl1U8l9Vg-XnxSPSg4IJdV_ARTsUdQ5mqHGcLHa0J5uBeHrxJbVIcoPIkDMztjZ1Y8hg0QnB4FM-EIJhg7RmMt9NC8AuAOG60i9Mco5sd39Yu9ysKGfHCLYNR8fnAepA7p1A_wBGYJ1uNz0j1cDNhGmznD1CNsdOcTJtBzlud4MDDGkFYfKbUD03SogAgtr9AYhvMsKKdY0fNHFVdaM2UoPAlNC86VIffZ4ZEbP_P2Z4zXpzSiamYbPDOAFAtPyaorsNQdfa1MBhfwMGrzePCaDD3JfKBPFEeoBTJg4tCUootl7A2Z1BUqqEuNFtHzT0TupGDgJRpVui7iJkV4vXCsMgIkyFfh9gIFG1FJrpcCL5hqS2tbF2TcFB0oJvetyqVbK0BY1u29J59e4mjDHBdcfLaOC3tEa0fJ9KyqxjfbDwuzWzwXl55ghgoeLeOH1tVfWPknG8-cizkRIblO7aqK6L_zAe2co_jYhMpou4KLBqRi9ZpHC-Z9MkZYN41HNmphDam1XPEJ65r5NDBB2qly88Njio-JDRNBrH7q9YEHBBCeGJ_CRs-UMSei2fRf2TWTDB-1K9L86bv6q4GnPrpLSrzWiR9fPxv1RVBNBRznyZobJHlC.ge6suqPkJCeHzMhDrb_kCw";


class Info{
    function Show($show): void {

        $ch = curl_init("https://services.radio-canada.ca/ott/catalog/v2/toutv/show/".$show->id."?device=web");
    
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE
        ));
        
        $str_response = curl_exec($ch);
        
        $resp = json_decode($str_response, TRUE);
        
        curl_close($ch);

        $show->title = $resp["title"];
        $show->description = $resp["description"];
        $show->image = $resp["images"]["background"]["url"];

        foreach ($resp["content"][0]["lineups"] as $_ => $s) {
            $season = new Season();
            array_push($show->seasons, $season);

            $season->title = $s["title"];
            $season->number = $s["seasonNumber"];

            foreach ($season as $_ => $e) {
                if ($e["mediaType"] == "Trailer") {
                    continue;
                }

                $episode = new Episode();
                array_push($season->episodes, $episode);

                $episode->id = $e["idMedia"];
                $episode->title = $e["title"];
                $episode->description = $e["description"];
                $episode->image = $e["images"]["card"]["url"];
                $episode->number = $e["episodeNumber"];
            }
        }
    }

    function Episode($episode): void {

        $ch = curl_init("https://services.radio-canada.ca/media/meta/v1/index.ashx?appCode=toutv&output=jsonObject&idMedia=".$episode->id);
    
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE
        ));
        
        $str_response = curl_exec($ch);
        
        $resp = json_decode($str_response, TRUE);
        
        curl_close($ch);

        $episode->id = $resp["Metas"]["idMedia"];
        $episode->title = $resp["Metas"]["Title"];
        $episode->description = $resp["Metas"]["Description"];
        $episode->image = $resp["Metas"]["imagePlayerNormalC"];
        $episode->number = (int)$resp["Metas"]["SrcEpisode"];
        $episode->service = "toutv";

        $episode->contains_drm = (bool)$resp["Metas"]["isDrmActive"];

        
    }
}