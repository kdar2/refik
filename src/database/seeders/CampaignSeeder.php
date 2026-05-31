<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $cat = fn (string $slug) => CampaignCategory::where('slug', $slug)->value('id');
        $cnt = fn (string $code) => Country::where('code', $code)->value('id');

        // [title, subtitle, description, category, country, cover_image, goal, raised, donor_count,
        //   zakat, sadaka, fitre, kurban, featured, emergency, order]
        $rows = [
            [
                'Gazze Acil Sıcak Yemek',
                'Gazze\'de aç kalmasın diye hareketteyiz',
                'Gazze\'deki kardeşlerimize her gün binlerce sıcak yemek ulaştırıyoruz. Senin desteğinle bu sofraları büyütmek istiyoruz.',
                'gazze-acil', 'PSE', '/storage/filisitine-yardim.png', 250000, 187500, 1248,
                false, true, false, false, true, true, 1,
            ],
            [
                'Gazze Temiz İçme Suyu',
                'Suyu olmayanın umudu olalım',
                'Gazze\'de temiz içme suyu için tankerler, su arıtma istasyonları ve günlük su dağıtımı sürdürüyoruz.',
                'gazze-acil', 'PSE', '/storage/filisitine-yardim.png', 180000, 145000, 980,
                false, true, false, false, true, true, 2,
            ],
            [
                'Yetim Sponsorluğu',
                'Bir yetimin yüzü senin bahtın olsun',
                'Aylık sponsorluk ile bir yetimin eğitim, beslenme ve barınma masraflarını üstlenebilirsin. Düzenli bağış ile devam edebilirsin.',
                'yetim', null, '/storage/ic-sayfa-detay-1.jpg', 200000, 78000, 412,
                true, true, false, false, true, false, 3,
            ],
            [
                'Su Kuyusu Aç',
                'Suyun aktığı yerde hayat var',
                'Afrika ve Güney Asya\'da derin su kuyusu açıyoruz. Bir kuyu, ortalama 1.500 kişiye temiz suya erişim sağlıyor.',
                'su-kuyusu', 'TCD', 'https://picsum.photos/seed/su-kuyusu/1200/675', 150000, 92500, 318,
                false, true, false, false, true, false, 4,
            ],
            [
                'İlim Yolcusuna Destek',
                'Senin desteğinle okusun',
                'İlim yolunda yürüyen talebelerimizin eğitim masraflarını birlikte üstlenelim; bursumuz, kitap ve barınma desteğimizle yanlarında olalım.',
                'egitim', 'TUR', '/storage/refik_image_1.png', 120000, 64000, 285,
                true, true, false, false, true, false, 5,
            ],
            [
                'Sudan\'a Umut Olmaya Çalışalım',
                'Açlık ve çatışmanın ortasında',
                'Nisan 2023\'ten bu yana Sudan\'da süren çatışmalarla milyonlarca kişi çaresiz kaldı. Gıda, sağlık ve barınma desteğimiz devam ediyor.',
                'sudan', 'SDN', 'https://picsum.photos/seed/sudan/1200/675', 180000, 100000, 524,
                false, true, false, false, true, true, 6,
            ],
            [
                'Suriye Genel Bağış',
                'Yarım kalan hayatlara umut',
                'Suriye\'deki iç savaş mağdurlarına gıda, sağlık, barınma ve eğitim desteği ulaştırıyoruz.',
                'suriye', 'SYR', '/storage/suriye.png', 250000, 140000, 612,
                false, true, false, false, true, false, 7,
            ],
            [
                'Kurban 2026',
                'Bu bayram yalnız olmasınlar',
                'Kurbanını ihtiyaç sahiplerinin sofrasına ulaştır; Türkiye, Filistin, Sudan, Suriye ve Somali başta olmak üzere 14 ülkede vekaletle kurban hizmeti.',
                'kurban', null, '/storage/kurban-1.png', 500000, 220000, 410,
                false, false, false, true, true, false, 8,
            ],
            [
                'Yurt Dışı Vacip Kurban',
                'Vekaletle yurt dışında kurban',
                'Filistin, Sudan, Suriye ve Somali başta olmak üzere ihtiyaç sahibi bölgelerde vekaletle vacip kurban kesimi.',
                'kurban', null, '/storage/yurt-disi-vacip.png', 80000, 32000, 145,
                false, false, false, true, false, false, 13,
            ],
            [
                'Yurt İçi Vacip Kurban',
                'Türkiye\'de ihtiyaç sahiplerine kurban',
                'Yurt içinde belirlediğimiz ihtiyaç sahibi ailelere vekaletle vacip kurban hizmeti.',
                'kurban', 'TUR', '/storage/yurt-ici-vacip.png', 60000, 24000, 110,
                false, false, false, true, false, false, 14,
            ],
            [
                'Adak Kurbanı',
                'Adağınız ihtiyaç sahibinin sofrasında',
                'Vekaletle adak kurbanınızı kesip etini ihtiyaç sahibi ailelere ulaştırıyoruz.',
                'kurban', null, '/storage/yurt-disi-adak.png', 40000, 18000, 78,
                false, false, false, true, false, false, 15,
            ],
            [
                'Sünnet Kurbanı',
                'Sünnetiniz hayır olsun',
                'Sünnet vesilesiyle adanan kurbanları yurt dışında ihtiyaç sahibi ailelere vekaletle ulaştırıyoruz.',
                'kurban', null, '/storage/yurt-disi-sunnet.png', 30000, 12000, 56,
                false, false, false, true, false, false, 16,
            ],
            [
                'Ramazan Gıda Paketi',
                'Bir yetime, bir aileye sofra',
                'Aile başına standart gıda paketleri ile ramazan ayında ihtiyaç sahibi ailelerin sofrasına misafir oluyoruz.',
                'gida', null, '/storage/refik-kumanya-bagis-kapak.jpg', 200000, 95000, 487,
                true, true, true, false, false, false, 9,
            ],
            [
                'Köy Okulları Kütüphane Projesi',
                'Her köyde bir kütüphane',
                'Türkiye\'nin küçük köylerindeki okullara kütüphane kuruyor, çocuklarımızı kitapla buluşturuyoruz.',
                'egitim', 'TUR', 'https://picsum.photos/seed/kutuphane/1200/675', 80000, 38000, 152,
                false, true, false, false, false, false, 10,
            ],
            [
                'Yemen Sağlık Yardımı',
                'Bebekler için ilaç ve gıda',
                'Yemen\'de süren krize karşı sağlık merkezleri ile işbirliği içinde ilaç, mama ve hijyen kiti dağıtıyoruz.',
                'saglik', 'YEM', 'https://picsum.photos/seed/yemen/1200/675', 150000, 47000, 198,
                false, true, false, false, false, false, 11,
            ],
            [
                'Afet Bölgesi Konteyner Barınma',
                'Soğuk geceler için sıcak yuva',
                'Deprem ve afet bölgelerinde yıkılan evlerin yerine geçici konteyner ve prefabrik barınma sağlıyoruz.',
                'barinma', 'TUR', 'https://picsum.photos/seed/barinma/1200/675', 300000, 125000, 340,
                false, true, false, false, false, false, 12,
            ],
        ];

        foreach ($rows as [$title, $sub, $desc, $catSlug, $cntCode, $image, $goal, $raised, $donors,
                          $z, $sc, $f, $k, $featured, $emergency, $order]) {
            Campaign::updateOrCreate(
                ['title_tr' => $title],
                [
                    'subtitle_tr'      => $sub,
                    'description_tr'   => "<p>{$desc}</p>",
                    'category_id'      => $catSlug ? $cat($catSlug) : null,
                    'country_id'       => $cntCode ? $cnt($cntCode) : null,
                    'cover_image'      => $image,
                    'goal_amount'      => $goal,
                    'raised_amount'    => $raised,
                    'currency'         => 'TRY',
                    'donor_count'      => $donors,
                    'zakat_eligible'   => $z,
                    'sadaka_eligible'  => $sc,
                    'fitre_eligible'   => $f,
                    'kurban_eligible'  => $k,
                    'is_featured'      => $featured,
                    'is_emergency'     => $emergency,
                    'is_active'        => true,
                    'order'            => $order,
                    'start_date'       => now()->subMonths(2),
                ],
            );
        }
    }
}
