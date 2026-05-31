<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'eyebrow_tr'     => 'Senin Desteğinle Okusun',
                'title_tr'       => 'Sadakan Sonsuz Olsun',
                'subtitle_tr'    => 'İlim yolunda yürüyen talebelerimizin eğitim masraflarını üstlenerek ilim ehli nesillerin yetişmesine vesile olabilirsiniz.',
                'image'          => '/storage/refik_image_1.png',
                'cta_text_tr'    => 'İlme Yoldaş Ol',
                'cta_url'        => '/calismalarimiz?category=egitim',
            ],
            [
                'eyebrow_tr'     => 'Kurban 2026',
                'title_tr'       => 'Bu Bayram Yalnız Olmasınlar',
                'subtitle_tr'    => 'Kurbanını ihtiyaç sahiplerinin sofrasına ulaştır, dünyanın dört bir yanında bayrama ortak ol.',
                'image'          => '/storage/kurban-1.png',
                'cta_text_tr'    => 'Kurban Bağışı Yap',
                'cta_url'        => '/calismalarimiz?category=kurban',
            ],
            [
                'eyebrow_tr'     => 'Gazze Acil',
                'title_tr'       => 'Bir Lokma da Senden',
                'subtitle_tr'    => 'Gazze\'deki kardeşlerimize sıcak yemek, gıda paketi ve temiz su ulaştırıyoruz. Sen de destek ol.',
                'image'          => '/storage/filisitine-yardim.png',
                'cta_text_tr'    => 'Hemen Destek Ol',
                'cta_url'        => '/calismalarimiz?category=gazze-acil',
            ],
            [
                'eyebrow_tr'     => 'Su Kuyusu',
                'title_tr'       => 'Suyun Aktığı Yerde Hayat Var',
                'subtitle_tr'    => 'Afrika\'nın susuzluk çeken bölgelerinde açtığımız su kuyularıyla binlerce hayata umut oluyoruz.',
                'image'          => '/storage/refik-kumanya-bagis-kapak.jpg',
                'cta_text_tr'    => 'Su Kuyusu Aç',
                'cta_url'        => '/calismalarimiz?category=su-kuyusu',
            ],
            [
                'eyebrow_tr'     => 'Yetim Hatırı',
                'title_tr'       => 'Bir Yetimin Yüzü Senin Bahtın Olsun',
                'subtitle_tr'    => 'Yetim sponsorluğu ile bir çocuğun eğitim, beslenme ve barınma masraflarını üstlenebilirsin.',
                'image'          => '/storage/ic-sayfa-detay-1.jpg',
                'cta_text_tr'    => 'Yetim Sponsoru Ol',
                'cta_url'        => '/calismalarimiz?category=yetim',
            ],
        ];

        foreach ($rows as $i => $row) {
            Slider::updateOrCreate(
                ['title_tr' => $row['title_tr']],
                array_merge($row, [
                    'order'           => $i + 1,
                    'is_active'       => true,
                    'overlay_color'   => '#0B295C',
                    'overlay_opacity' => 40,
                ]),
            );
        }
    }
}
