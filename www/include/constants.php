<?php

	##########################################
	# 상수설정 Web Site Global Variables
	# FUNCTION Configuration
	##########################################
	
	$C_FUNC['test']						= "시험";
	$C_FUNC['foreshow_query']			= "오늘의 예시질문내용 얻기";
	$C_FUNC['foreshow_answer']			= "이성의 예시질문내용 답변 처리";
	$C_FUNC['foreshow_matchlist']		= "예시매칭리스트 얻기";
	$C_FUNC['get_usage_policy']			= "이용약관 및 개인정보취급방침내용 얻기";
	$C_FUNC['find_address']				= "주소검색요청";
	$C_FUNC['check_email']				= "이메일중복확인";
	$C_FUNC['check_userid']				= "유저아이디중복확인";
	$C_FUNC['get_auth_code']			= "사용자인증번호발송";
	$C_FUNC['verify_auth_code']			= "인증번호확인요청";
	$C_FUNC['register_new_user']		= "회원등록요청";
	$C_FUNC['login_user']				= "회원가입요청";
	$C_FUNC['find_userid']				= "유저아이디찾기요청";
	$C_FUNC['find_password']			= "유저패스워드찾기요청";	
	$C_FUNC['download_image']			= "이미지다운로드 요청";
	$C_FUNC['upload_main_photo']		= "메인사진업로드 요청";
	$C_FUNC['delete_main_photo']		= "메인사진삭제 요청";
	$C_FUNC['upload_photo']				= "보조사진업로드 요청";
	$C_FUNC['delete_photo']				= "보조사진삭제 요청";
	$C_FUNC['get_profile']				= "프로파일얻기 요청";
	$C_FUNC['update_main_profile']		= "기본정보수정요청";
	$C_FUNC['update_detail_profile']	= "세부정보수정요청";
	$C_FUNC['update_love_profile']		= "연애정보수정요청";
	$C_FUNC['get_participate_state']	= "참여상태요청";
	$C_FUNC['set_state_finishanswer']	= "질문완료상태설정";
	$C_FUNC['select_query']				= "오늘의 질문내용 얻기";
	$C_FUNC['answer_query']				= "오늘의 질문내용 답변";
	$C_FUNC['match']					= "오늘의 매칭진행";
	$C_FUNC['match_once']				= "오늘의 매칭 한번 더 진행";
	$C_FUNC['get_matchlist']			= "오늘의 매칭리스트 얻기";
	$C_FUNC['select_query2']			= "오늘의 이성의 질문내용 얻기";
	$C_FUNC['answer_query2']			= "오늘의 이성의 질문내용 답변";
	$C_FUNC['touch_dropout']			= "탈락자이성의 터치처리";
	$C_FUNC['silhouette']				= "오늘의 이성의 실루엣처리";
	$C_FUNC['send_specialquery']		= "스페셜질문전송요청";
	$C_FUNC['view_specialquery']		= "스페셜질문내용보기요청";
	$C_FUNC['answer_specialquery']		= "스페셜질문답변";
	$C_FUNC['view_specialanswer']		= "스페셜질문답변내용요청";
	$C_FUNC['check_specialquery']		= "스페셜질문답변체크";
	$C_FUNC['verify_specialquery']		= "스페셜질문답변체크결과확인요청";
	$C_FUNC['set_charm']				= "매력지수설정";
	$C_FUNC['leave_chat']				= "대화하기선택요청";
	$C_FUNC['leave_history']			= "히스토리남기기요청";
	$C_FUNC['leave_cancel']				= "매칭취소선택요청";
	$C_FUNC['get_new_results']			= "새결과목록얻기요청";
	$C_FUNC['get_confirm_results']		= "확인목록얻기요청";
	$C_FUNC['open_chat']				= "채팅방오픈";
	$C_FUNC['send_message']				= "이성회원에게 메쎄지 전송";
	$C_FUNC['get_message_list']		= "채팅리력리스트 얻기";
	$C_FUNC['report_bug']				= "버그제보&문의요청";
	$C_FUNC['change_password']			= "비밀번호변경을 진행한다.";
	$C_FUNC['withdraw']					= "해당 계정을 삭제한다.";
	$C_FUNC['change_rest']				= "계정휴면설정을 변경한다.";
	$C_FUNC['change_pushalrim']			= "푸시알림설정을 변경한다.";
	$C_FUNC['init_charm']				= "매력지수를 초기화한다.";
	$C_FUNC['get_store_items']			= "스토어의 아이템리스트 얻기";
	$C_FUNC['buy_item']					= "스토어의 아이템결제";	
	$C_FUNC['get_news']					= "신규내용확인요청";
	$C_FUNC['get_newpush']				= "새로운 푸시메시지요청";
	$C_FUNC['viewpush']					= "푸시메시지 보기요청";
	$C_FUNC['get_freecoin_list']		= "무료풍선목록얻기요청";
	$C_FUNC['select_freecoin']			= "무료풍선선택요청";
	$C_FUNC['invite_friend']			= "친구초대";
	$C_FUNC['report']					= "신고요청";
	$C_FUNC['get_settinginfo']			= "본인의 설정정보 요청";
	$C_FUNC['view_myfavor']				= "최근 호감도보기정보 얻기";
	$C_FUNC['update_myfavor']			= "호감도확인요청";
	$C_FUNC['get_chatlist']				= "대화함 리스트얻기요청";
	$C_FUNC['register_tokenid']			= "전화기 토큰아이디 등록";
	
	##########################################
	# MESSAGE Configuration
	##########################################
	
	$C_MSG['success']								= "조작이 성공하였습니다.";
	$C_MSG['fail']									= "조작이 실패하였습니다.";
	$C_MSG['no_data']								= "자료가 없습니다.";
	$C_MSG['email_dup']								= "이메일중복";
	$C_MSG['userid_dup']							= "유저아이디중복";
	$C_MSG['noreg_user']							= "유저 등록되지 않음";	
	$C_MSG['noreg_pwd']								= "비밀번호가 부정확";
	$C_MSG['noreg_email']							= "이메일 등록되지 않음";
	$C_MSG['noreg_userinfo']						= "당신의 아이디나 이메일이 등록되지 않음";	
	$C_MSG['no_query']								= "질문내용 없음";
	$C_MSG['no_answer']								= "답변내용 없음";
	$C_MSG['fail_mkdir']							= "디렉토리생성이 실패하였습니다.";
	$C_MSG['fail_upload']							= "파일업로드가 실패하였습니다.";
	$C_MSG['fail_movefile']							= "파일이동이 실패하였습니다.";
	$C_MSG['fail_smssend']							= "SMS송신이 실패하였습니다.";
	$C_MSG['no_chatroom']							= "채팅방이 오픈되지 않았습니다.";
	$C_MSG['noreg_freecoin']						= "무료코인이 등록되지 않음";
	$C_MSG['noreg_billitem']						= "스토어아이템이 등록되지 않음";
	$C_MSG['want_coin']								= "코인부족";	
	$C_MSG['matched']								= "이미 매칭 진행";
	$C_MSG['no_participate']						= "참여없음";
	$C_MSG['sended_specialquery']					= "스페셜질문 이미 전송하였음";
	
	$C_MSG['recv_specialquery']						= "스페셜질문이 도착하였습니다.\n";
	$C_MSG['recv_specialanswer']					= "스페셜답변이 도착하였습니다.\n";
	$C_MSG['recv_specialcheck']						= "스페셜답변결과가 도착하였습니다.\n";
	$C_MSG['touch_dropout']							= "당신의 프로필을 터치하였습니다.\n";
	$C_MSG['sender']								= "보낸이: \n";
	$C_MSG['pushcontent']							= "내용: \n";
	$C_MSG['answer_right']							= "답변성공";
	$C_MSG['answer_wrong']							= "답변실패";
	$C_MSG['open_chatroom']							= "채팅방을 오픈하였습니다.";
	
	##########################################
	# VALUE Configuration
	##########################################

	$C_VAL['invite_login']							= 10;	// 초대코드로 가입한 친구가 10명이 넘을때 하트 추가 지급.
	$C_VAL['days_off_allowmatch']					= 7;	// 이성추천을 OFF한지 7일이 되였는가?
?>