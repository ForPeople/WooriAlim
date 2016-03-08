<?php
// normal
$lang->donotsendlist = '수신거부 관리';
$lang->configure = '設定';
$lang->boardmailing = '게시판 메일링/댓글알림/뉴스레터 관리';
$lang->manual = '사용자 가이드';
$lang->insertmembers = '외부회원 등록 및 관리';
$lang->regdate = '登録日';
$lang->delete = '削除';
$lang->insert_mail = '1.登録';
$lang->setup_send = '2.設定';
$lang->send_list = '3.送信';
$lang->title = 'メールタイトル';
$lang->content = 'メール内容';
$lang->preview = 'プレビュー';
$lang->sender_nickname = '送信者ニックネーム';
$lang->sender_email = '送信者メールアドレス';
$lang->receive_email = '受信メール';
$lang->receive_nickname = '受信ニックネーム';
$lang->wantdel = '삭제하시겠습니까?';

// baordmailing


// config
$lang->warning = '우리알림 모듈은 포피플이 개발하고 배포하는 대량메일 솔루션 입니다.<br />
우리알림 모듈은 포피플의 우리메일(http://woorimail.com) 시스템을 이용하여 서비스 됩니다.<br />
우리알림 모듈을 통해 월 1만통 이메일을 무료로 사용하실 수 있습니다.<br />
서비스의 올바른 사용을 위하여 항상 우리알림의 최신 버전을 유지하는 것을 권장합니다.';
$lang->accept_agree = '우리알림 주의사항을 숙지하였고 모든 내용에 동의합니다';
$lang->must_insert = '(*)表示は、必ず入力して保存します。';
$lang->insertauthkey = '인증키입력';
$lang->authcenter = '발급처';
$lang->insert_url = '입력할 도메인';
$lang->type_replymail = '대량메일 전송타입 설정';
$lang->type_replymail_f = '회신없는 대량메일';

// footer
$lang->no_num = '一度に送信する人員を数字で入力して保存してください。';
$lang->no_member = 'メールを送信する会員がいません。';
$lang->save_ok = '保存しました。';

// header
$lang->woorialim = '우리알림';
$lang->mailing_status = '우리메일 서버 상황 및 나의 포인트';
$lang->authcheck = '통신상태';
$lang->paymail_ready = '유료메일 대기열';
$lang->freemail_ready = '무료메일 대기열';
$lang->promotion_point = '프로모션 포인트';
$lang->free_point = '무료 포인트';
$lang->etc_point = '기타 포인트';
$lang->pay_point = '유료 포인트';
$lang->refresh = '상태 재확인';

// index
$lang->about_woorialim = '1. 회원 가입시 이메일 주소가 정확하지 않으면 이메일이 도착하지 않습니다.
2. 웹서버에 메일서버가 작동하지 않고 있어도 우리메일서버를 통해 메일이 전송됩니다.
3. 첨부파일은 지원하지 않으며 본문에 링크를 첨부해서 메일 보내시기 바랍니다.(대량 메일 특성상 트래픽 문제가 발생할 수 있으니 웹호스팅 유저는 주의 바랍니다.) - 단독형
4. 프로모션 포인트 > 무료 포인트 > 기타 포인트 > 유료 포인트 순으로 차감됩니다.
5. 유료메일은 항상 최우선적으로 처리되며 모든 유료메일이 전송된 후 무료메일이 처리됩니다.
6. 유료메일이 정상적으로 발송되지 않았을 경우 기타포인트로 익일 재충전 됩니다.(무료 포인트 제외)
';
$lang->menutitle = '메뉴명';
$lang->desc = '설명';
$lang->manual_configure = '設定';
$lang->manual_configure_content = '인증키를 입력하고 각종 항목을 설정할 수 있습니다.';
$lang->manual_insert = '1. 内容登録';
$lang->manual_insert_content = '이메일을 XE 내장 에디터로 작성할 수 있습니다.<br />
작성한 이메일은 바로 전송되는 것이 아니고 한번 저장되어 [전송조건 세팅] 메뉴로 넘어갑니다.<br />
파일 첨부의 경우 php에서 설정한 한계 용량까지 에디터를 이용하여 첨부 가능합니다.<br />
에디터를 이용하여 html로 작성이 가능합니다.';
$lang->manual_mailsetup = '2. 送信条件設定';
$lang->manual_mailsetup_content = '저장된 메일내용에 대해 전송대상 그룹을 선택하고 수신허용필터 기능을 설정하여 전송 준비합니다.<br />
메일 내용을 수정하거나 삭제할 수 있습니다.<br />
전송준비 버튼을 누르면 [전송대기 리스트] 메뉴로 넘어갑니다.<br />';
$lang->manual_sendready = '3. メール送信';
$lang->manual_sendready_content = '한번에 전송할 회원수에 맞게 나눠서 전송대기 상태로 등록됩니다.<br />
잘못 등록했을 시 삭제 버튼으로 삭제가 가능하며 다시 [전송조건 세팅]에서 재 등록 해주시면 됩니다.<br />
전송시작을 누르면 전송중 상태로 전환되며 우리메일 시스템이 자동으로 전송을 도와줍니다.<br />';

$lang->manual_insertmembers = '외부회원 등록 및 관리';
$lang->manual_insertmembers_content = '1. 엑셀파일을 업로드 하는 방식으로 회원을 대량등록하고, 삭제 할 수 있도록 합니다.<br />
2. DB연동을 통해 타 홈페이지 회원들을 하나의 그룹으로 관리할 수 있습니다.';
$lang->manual_changestr = '置換変数';
$lang->manual_changestr_content = '제목과 내용등록 시 {nickname},{email} 치환변수를 사용하면<br />
받는회원 닉네임과 이메일주소가 자동으로 삽입됩니다.<br />
치환변수를 하나의 단어로 간주하고 html을 적용할 수 있습니다.<br />';
$lang->manual_boardmailing = '게시판 메일링/댓글알림/뉴스레터 관리';
$lang->manual_boardmailing_content = '<a href=http://www.xpressengine.com/index.php?mid=download&package_id=22753306 target=_blank>게시판 메일링 위젯(GG Board Mailing Widget)</a> 을 이용하여 게시판 메일링을 구현할 수 있습니다.<br />
게시판 댓글알림 위젯(GG Ward Widget) 을 이용하여 게시판 댓글알림을 구현할 수 있습니다.<br />
뉴스레터 위젯(GG Newsletter Widget) 을 이용하여 뉴스레터 메일링을 구현할 수 있습니다.<br />
게시판별 메일링/댓글알림/뉴스레터 가입 회원을 관리합니다.';

// list
$lang->send_to_all = '全送る';
$lang->allow_mailing = 'メール受信を許可会員だけ';
$lang->all_mailing = 'メール受信可否無視';
$lang->send_ready = '送信準備';
$lang->modify = '修正';

// mailing
$lang->sender = 'メール内容の作成完了';
$lang->select_group = 'メール受信グループの選択';
$lang->mailing_allow = 'メールの受信許可フィルタ';

// preview
$lang->back = '戻る';

// send
$lang->send_start = '送信開始';
$lang->outof = '外';
$lang->persons = '人';
$lang->email = 'メール';
$lang->send_complete = '送信完了';











?>
