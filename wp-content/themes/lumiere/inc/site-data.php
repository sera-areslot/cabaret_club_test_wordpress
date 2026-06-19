<?php
/**
 * サイト共通データ（店舗情報・料金・コンセプト等）。
 *
 * 現状はここで一元管理。Phase 後半で管理画面（設定ページ）化できるよう
 * `lumiere_site_data` フィルターで上書き可能にしています。
 *
 * @package lumiere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array
 */
function lumiere_site() {
	return apply_filters( 'lumiere_site_data', array(
		'wordmark'     => 'LUMIÈRE',
		'area'         => '大阪・北新地',
		'hero_ja'      => '夜を、芸術に。',
		'hero_lead'    => '一夜の記憶を、最上の美と静けさで満たす。',

		'concept_lead' => '静謐な高揚。',
		'concept_text' => array(
			'きらびやかさではなく、静けさの中にこそ宿る品格を。設えのひとつひとつ、グラスの傾き、交わす言葉の余白まで。北新地の夜に、もうひとつの「特別」を仕立てました。',
			'派手さで魅せるのではなく、上質な時間そのもので満たす。それが LUMIÈRE のもてなしです。',
		),

		'system'       => array(
			array( 'name' => 'セット料金', 'note' => '60分', 'price' => 8000 ),
			array( 'name' => 'ご指名',     'note' => '1名',  'price' => 3000 ),
			array( 'name' => '同伴出勤',   'note' => '',      'price' => 5000 ),
			array( 'name' => 'VIP ルーム', 'note' => '1卓',  'price' => 20000 ),
		),
		'system_note'  => '表示は税・サービス料別の目安です。詳細は店舗までお問い合わせください。',

		'recruit_lead' => '北新地で、あなたらしい夜の物語を。',
		'recruit_text' => '未経験から活躍できる環境を整えています。体験入店・短期もお気軽にご相談ください。',

		'store'        => array(
			'店名'     => 'CLUB LUMIÈRE（仮）',
			'住所'     => '〒530-0002 大阪市北区曽根崎新地 1-0-00（仮）',
			'電話'     => '06-0000-0000',
			'営業時間' => '20:00 – 翌1:00',
			'定休日'   => '日曜・祝日',
		),

		'sns'          => array(
			'Instagram' => '#',
			'X'         => '#',
			'LINE'      => '#',
		),
	) );
}
