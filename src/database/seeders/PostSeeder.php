<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $saha = PostCategory::where('slug', 'saha-haberleri')->value('id');
        $duy  = PostCategory::where('slug', 'duyurular')->value('id');
        $bas  = PostCategory::where('slug', 'basinda-biz')->value('id');
        $etk  = PostCategory::where('slug', 'etkinlikler')->value('id');

        $rows = [
            // [title, excerpt, category_id, featured, days_ago]
            ['Köy Okulları Projesi: Yayla Köyü İlkokulu\'nu Ziyaret Edip Kütüphanemizi Açtık',
             '9 merkezde açtığımız ücretsiz sınavlara hazırlık kursları, Destek Eğitim Merkezi adı altında gençleri LGS ve YKS sınavlarına hazırlıyor.',
             $saha, true, 12],

            ['Sevgi Evleri Ziyaretimiz: Minik Kardeşlerimize Bayram Sürprizi Yaptık',
             'Bayram coşkusunu Sevgi Evleri\'nde paylaştık; oyuncak ve kıyafet hediyeleriyle birlikte minik kardeşlerimizle güzel anlar biriktirdik.',
             $saha, false, 25],

            ['Huzurevi Sakinlerini Ziyaret Edip Büyüklerimizin Hayır Duasını Aldık',
             'Gönüllülerimizle birlikte gerçekleştirdiğimiz ziyarette büyüklerimize hediyeler takdim ettik; tecrübelerinden istifade ettik.',
             $saha, false, 30],

            ['Engelsiz Yaşam Merkezi\'nde Düzenlediğimiz Kermes Büyük İlgi Gördü',
             'Engelli kardeşlerimizin el emeği ürünlerinin satışa sunulduğu kermesimiz, gönüllülerin desteğiyle dopdolu geçti.',
             $etk, false, 35],

            ['Sudan\'a Acil Gıda ve Sağlık Yardımı Ulaştırdık',
             'Çatışmaların ortasında kalan ailelere ramazan gıda paketi, ilaç ve hijyen kiti ulaştırdık. Saha ekibimiz bölgede çalışmalarına devam ediyor.',
             $saha, true, 8],

            ['Suriye\'de Yetim Sponsorluğu Programı Genişledi',
             'Bu yıl 240 yetim kardeşimiz daha sponsorluk programımıza dahil oldu; eğitim, beslenme ve barınma masrafları aylık olarak üstlenildi.',
             $duy, false, 18],

            ['Gazze Sıcak Yemek Dağıtımı 100. Gününe Ulaştı',
             '100 günde 19.500\'den fazla kişiye sıcak yemek ulaştırdık. Saha ekibimizin emek ve cesaretine, bağışçılarımızın cömertliğine teşekkür ederiz.',
             $saha, true, 3],

            ['Bağımsız Denetim Raporumuz Yayınlandı',
             '2025 yılı mali raporumuz bağımsız denetim firması tarafından onaylanarak yayınlandı; tam metni etki ve güvence sayfamızdan inceleyebilirsiniz.',
             $duy, false, 22],

            ['Kurban 2026 Vekaleti Başlıyor',
             'Bu yıl Türkiye, Filistin, Sudan, Suriye ve Somali başta olmak üzere 14 ülkede kurban vekaleti hizmeti vereceğiz.',
             $duy, true, 5],

            ['Refik Derneği Anadolu Ajansı\'nda Yer Aldı',
             'Saha çalışmalarımıza dair Anadolu Ajansı muhabirlerinin hazırladığı haber yayınlandı; basın bültenimize ulaşabilirsiniz.',
             $bas, false, 40],

            ['Yaz Eğitim Kampı 2026 Başvuruları Açıldı',
             '14-18 yaş aralığındaki gençleri ilim, sanat ve doğa odaklı yaz kampımıza bekliyoruz. Başvurular form üzerinden alınmaktadır.',
             $etk, false, 14],

            ['Ankara Gönüllü Buluşması\'nda Bir Araya Geldik',
             'Bölgesel gönüllü temsilcilerimiz Ankara\'da düzenlenen yıllık buluşmamızda yıl planını ve saha öncelikleri masaya yatırdı.',
             $etk, false, 28],
        ];

        foreach ($rows as $i => [$title, $excerpt, $cat, $featured, $daysAgo]) {
            Post::updateOrCreate(
                ['title_tr' => $title],
                [
                    'excerpt_tr'       => $excerpt,
                    'content_tr'       => "<p>{$excerpt}</p><p>Detaylı içerik yakında yayınlanacaktır.</p>",
                    'post_category_id' => $cat,
                    'cover_image'      => 'https://picsum.photos/seed/post-' . ($i + 1) . '/1200/675',
                    'is_featured'      => $featured,
                    'is_published'     => true,
                    'published_at'     => now()->subDays($daysAgo),
                ],
            );
        }
    }
}
