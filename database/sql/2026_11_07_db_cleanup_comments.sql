-- DB 정리 · 테이블/컬럼 설명(COMMENT) 반영 (2026-11-07)
-- 1) 현재 소스에서 사용하지 않는 테이블 삭제 (삭제 전 백업 권장 : 로컬 백업 storage/app/private/db_backup/2026_11_07_unused_tables_backup.sql)
-- 2) DB 명세서(RICHPORT_DB명세서_20261107.xlsx)의 테이블 설명 · 보완/수정한 컬럼 설명을 COMMENT 로 반영
--    컬럼 정의(타입, NULL, 기본값 등)는 그대로 두고 COMMENT 만 변경
-- DB 별로 1회 실행 (여러 번 실행해도 결과는 같음)

-- ===== 1. 미사용 테이블 삭제 =====
DROP TABLE IF EXISTS `category`;   -- 카테고리 (이전 시스템)
DROP TABLE IF EXISTS `check_sign`;   -- 체크리스트 서명 (이전 체크리스트 기능)
DROP TABLE IF EXISTS `department`;   -- 부서 (이전 체크리스트 기능)
DROP TABLE IF EXISTS `library`;   -- 자료실 (이전 시스템)
DROP TABLE IF EXISTS `library_files`;   -- 자료실 첨부파일 (이전 시스템)
DROP TABLE IF EXISTS `notice`;   -- 회사소식 (이전 시스템)
DROP TABLE IF EXISTS `notice_files`;   -- 회사소식 첨부파일 (이전 시스템)
DROP TABLE IF EXISTS `out_company`;   -- 외주 업체 (이전 시스템)
DROP TABLE IF EXISTS `out_type`;   -- 외주 업무 구분 (이전 시스템)
DROP TABLE IF EXISTS `out_user`;   -- 외주 업체 인력 (이전 시스템)
DROP TABLE IF EXISTS `testbed_inquire_types`;   -- 테스트베드 신청 구분 (현재 미사용, 신청 상품은 product 사용)
DROP TABLE IF EXISTS `users`;   -- 사용자 (Laravel 기본, 미사용)

-- ===== 2. 테이블 / 컬럼 설명 =====
ALTER TABLE `admin`
  COMMENT = '관리자 계정';

ALTER TABLE `admin_log`
  COMMENT = '관리자 작업 로그 (등록·수정·삭제·엑셀 다운로드)';

ALTER TABLE `admin_permissions`
  MODIFY `idx` int(10) NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `admin_idx` varchar(20) DEFAULT NULL COMMENT '관리자 (admin.idx)',
  MODIFY `permission_key` varchar(50) DEFAULT NULL COMMENT '권한 키 (config/permission.php, 예: product.view)',
  COMMENT = '관리자별 메뉴 권한';

ALTER TABLE `check_execution`
  MODIFY `idx` int(10) NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `list_idx` int(10) DEFAULT NULL COMMENT '설문 (check_list.idx)',
  MODIFY `project_idx` int(10) DEFAULT NULL COMMENT '프로젝트 (이전 체크리스트용, 설문 미사용)',
  MODIFY `project_name` varchar(100) DEFAULT NULL COMMENT '프로젝트명 (이전 체크리스트용)',
  MODIFY `user_comp` varchar(100) DEFAULT NULL COMMENT '응답자 회사 (이전 체크리스트용)',
  MODIFY `user_name` varchar(50) DEFAULT NULL COMMENT '응답자 성명 (이전 체크리스트용)',
  MODIFY `user_phone` varchar(20) DEFAULT NULL COMMENT '응답자 전화번호 (이전 체크리스트용)',
  MODIFY `user_email` varchar(100) DEFAULT NULL COMMENT '응답자 이메일 (이전 체크리스트용)',
  MODIFY `user_lat` varchar(20) DEFAULT NULL COMMENT '응답 위치 위도 (이전 체크리스트용)',
  MODIFY `user_lng` varchar(20) DEFAULT NULL COMMENT '응답 위치 경도 (이전 체크리스트용)',
  MODIFY `user_sign` varchar(255) DEFAULT NULL COMMENT '서명 이미지 경로 (이전 체크리스트용)',
  MODIFY `use_yn` char(1) DEFAULT 'Y' COMMENT '사용여부 (Y: 집계 포함, N: 제외)',
  MODIFY `created_at` datetime DEFAULT current_timestamp() COMMENT '등록일시',
  MODIFY `created_by` varchar(50) DEFAULT NULL COMMENT '등록자 (설문 응답은 survey)',
  MODIFY `updated_at` datetime DEFAULT NULL COMMENT '수정일시',
  MODIFY `updated_by` varchar(50) DEFAULT NULL COMMENT '수정자',
  COMMENT = '설문 응답 (응답자 1명 = 1행)';

ALTER TABLE `check_item`
  MODIFY `idx` int(10) NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `section_idx` int(10) DEFAULT NULL COMMENT '섹션 (check_section.idx)',
  MODIFY `d2_name` varchar(100) DEFAULT NULL COMMENT '2단계 구분명 (섹션 레이아웃 2·3단)',
  MODIFY `d3_name` varchar(100) DEFAULT NULL COMMENT '3단계 항목명 (섹션 레이아웃 3단)',
  MODIFY `item_name` varchar(200) DEFAULT NULL COMMENT '문항',
  MODIFY `sort_order` int(5) DEFAULT 0 COMMENT '정렬순서',
  COMMENT = '설문 문항';

ALTER TABLE `check_list`
  MODIFY `idx` int(10) NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `check_name` varchar(100) DEFAULT NULL COMMENT '설문명',
  MODIFY `depart_idx` int(10) DEFAULT NULL COMMENT '부서 (이전 체크리스트용)',
  MODIFY `multi_sign` char(1) DEFAULT 'N' COMMENT '다중 서명 여부 (이전 체크리스트용)',
  MODIFY `use_yn` char(1) DEFAULT 'Y' COMMENT '사용여부 (Y: 응답 가능, N: 응답 불가)',
  MODIFY `created_at` datetime DEFAULT current_timestamp() COMMENT '등록일시',
  MODIFY `created_by` varchar(50) DEFAULT NULL COMMENT '등록자',
  MODIFY `updated_at` datetime DEFAULT NULL COMMENT '수정일시',
  MODIFY `updated_by` varchar(50) DEFAULT NULL COMMENT '수정자',
  COMMENT = '설문 (테스트베드 상품별)';

ALTER TABLE `check_option`
  MODIFY `idx` int(10) NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `item_idx` int(10) DEFAULT NULL COMMENT '문항 (check_item.idx)',
  MODIFY `option_name` varchar(100) DEFAULT NULL COMMENT '보기',
  MODIFY `sort_order` int(5) DEFAULT 0 COMMENT '정렬순서',
  COMMENT = '설문 문항 보기';

ALTER TABLE `check_result`
  MODIFY `idx` int(10) NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `exec_idx` int(10) DEFAULT NULL COMMENT '응답 (check_execution.idx)',
  MODIFY `list_idx` int(10) DEFAULT NULL COMMENT '설문 (check_list.idx)',
  MODIFY `section_idx` int(10) DEFAULT NULL COMMENT '섹션 (check_section.idx)',
  MODIFY `item_idx` bigint(20) unsigned NOT NULL COMMENT '문항 (check_item.idx)',
  MODIFY `input_type` varchar(10) DEFAULT NULL COMMENT '입력 방식 (응답 시점)',
  MODIFY `option_idx` varchar(20) DEFAULT NULL COMMENT '선택한 보기 (check_option.idx, 복수선택은 보기마다 1행)',
  MODIFY `result_value` text DEFAULT NULL COMMENT '주관식 답변 / 비고',
  COMMENT = '설문 문항별 응답값';

ALTER TABLE `check_section`
  MODIFY `idx` int(10) NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `list_idx` int(10) DEFAULT NULL COMMENT '설문 (check_list.idx)',
  MODIFY `section_name` varchar(100) DEFAULT NULL COMMENT '섹션명',
  MODIFY `sort_order` int(5) DEFAULT NULL COMMENT '정렬순서',
  MODIFY `layout_type` int(1) DEFAULT 3 COMMENT '레이아웃 (1: 섹션+문항, 2: 구분+문항, 3: 구분+항목+문항)',
  COMMENT = '설문 섹션';

ALTER TABLE `cities`
  MODIFY `idx` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `name_kr` varchar(50) NOT NULL COMMENT '시 이름 (한국어)',
  MODIFY `name_en` varchar(100) DEFAULT NULL COMMENT '시 이름 (영어)',
  MODIFY `name_vn` varbinary(100) DEFAULT NULL COMMENT '시 이름 (베트남어)',
  COMMENT = '지역 - 시 (베트남)';

ALTER TABLE `customs_issue`
  COMMENT = 'RIPS 통관 이슈';

ALTER TABLE `districts`
  MODIFY `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `cities_idx` tinyint(3) unsigned NOT NULL COMMENT '시 (cities.idx)',
  MODIFY `name_kr` varchar(100) NOT NULL COMMENT '구·군 이름 (한국어)',
  MODIFY `name_en` varchar(100) DEFAULT NULL COMMENT '구·군 이름 (영어)',
  MODIFY `name_vn` varchar(100) DEFAULT NULL COMMENT '구·군 이름 (베트남어)',
  COMMENT = '지역 - 구·군 (베트남)';

ALTER TABLE `faq`
  COMMENT = 'RIPS FAQ';

ALTER TABLE `form_types`
  MODIFY `subject` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '카테고리명 (한국어)',
  MODIFY `subject_en` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '카테고리명 (영어)',
  MODIFY `subject_vn` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '카테고리명 (베트남어)',
  COMMENT = '리치포트 카테고리 (1~4단계 트리)';

ALTER TABLE `history`
  MODIFY `subject_en` varchar(1000) DEFAULT NULL COMMENT '연혁 제목 (영어)',
  MODIFY `subject_vn` varchar(500) DEFAULT NULL COMMENT '연혁 제목 (베트남어)',
  COMMENT = '회사 연혁';

ALTER TABLE `inquire`
  MODIFY `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `types_idx` tinyint(3) NOT NULL DEFAULT 1 COMMENT '문의구분 (inquire_types.idx)',
  MODIFY `complete_at` datetime DEFAULT NULL COMMENT '처리완료일 (답변완료 시)',
  COMMENT = '고객문의';

ALTER TABLE `inquire_types`
  MODIFY `subject` varchar(200) NOT NULL DEFAULT '' COMMENT '구분명 (한국어)',
  MODIFY `subject_en` varchar(200) NOT NULL DEFAULT '' COMMENT '구분명 (영어)',
  MODIFY `subject_vn` varchar(200) NOT NULL DEFAULT '' COMMENT '구분명 (베트남어)',
  COMMENT = '고객문의 구분';

ALTER TABLE `partner`
  MODIFY `forms_idx` int(10) unsigned DEFAULT NULL COMMENT '카테고리 (form_types.idx)',
  MODIFY `country` varchar(50) DEFAULT NULL COMMENT '국가 (korea / vietnam)',
  MODIFY `cities_idx` tinyint(3) unsigned DEFAULT NULL COMMENT '시 (cities.idx)',
  MODIFY `districts_idx` int(10) unsigned DEFAULT NULL COMMENT '구·군 (districts.idx)',
  MODIFY `partner_name_en` varchar(500) NOT NULL DEFAULT '' COMMENT '업체명 (영어)',
  MODIFY `partner_name_vn` varchar(200) NOT NULL DEFAULT '' COMMENT '업체명 (베트남어)',
  MODIFY `channel_en` varchar(200) DEFAULT NULL COMMENT '주요 유통채널 (영어)',
  MODIFY `channel_vn` varchar(200) DEFAULT NULL COMMENT '주요 유통채널 (베트남어)',
  MODIFY `pc_ori_name` varchar(255) DEFAULT NULL COMMENT 'PC 썸네일 원본 파일명',
  MODIFY `pc_up_name` varchar(255) DEFAULT NULL COMMENT 'PC 썸네일 저장 경로',
  MODIFY `pc_file_ext` varchar(20) DEFAULT NULL COMMENT 'PC 썸네일 확장자',
  MODIFY `pc_file_size` bigint(20) unsigned DEFAULT NULL COMMENT 'PC 썸네일 파일 크기 (byte)',
  MODIFY `fo_ori_name` varchar(255) DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 원본 파일명',
  MODIFY `fo_up_name` varchar(255) DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 저장 경로',
  MODIFY `fo_file_ext` varchar(20) DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 확장자',
  MODIFY `fo_file_size` bigint(20) unsigned DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 파일 크기 (byte)',
  MODIFY `mo_ori_name` varchar(255) DEFAULT NULL COMMENT '모바일 썸네일 원본 파일명',
  MODIFY `mo_up_name` varchar(255) DEFAULT NULL COMMENT '모바일 썸네일 저장 경로',
  MODIFY `mo_file_ext` varchar(20) DEFAULT NULL COMMENT '모바일 썸네일 확장자',
  MODIFY `mo_file_size` bigint(20) unsigned DEFAULT NULL COMMENT '모바일 썸네일 파일 크기 (byte)',
  MODIFY `contents_en` text DEFAULT NULL COMMENT '소개글 (영어)',
  MODIFY `contents_vn` text DEFAULT NULL COMMENT '소개글 (베트남어)',
  COMMENT = '파트너 · RIPS 업체 (홈페이지 파트너, RIPS 업체 계정·정보)';

ALTER TABLE `partner_files`
  MODIFY `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `partner_idx` int(10) unsigned NOT NULL COMMENT '파트너 (partner.idx)',
  MODIFY `orderby` int(10) unsigned DEFAULT NULL COMMENT '정렬순서',
  COMMENT = '파트너 이미지';

ALTER TABLE `portfolio`
  MODIFY `types_idx` int(10) unsigned NOT NULL COMMENT '유형 (types.idx)',
  MODIFY `station_name` varchar(200) NOT NULL COMMENT '업체명 (한국어)',
  MODIFY `station_name_en` varchar(500) NOT NULL DEFAULT '' COMMENT '업체명 (영어)',
  MODIFY `station_name_vn` varchar(200) NOT NULL DEFAULT '' COMMENT '업체명 (베트남어)',
  MODIFY `pc_ori_name` varchar(255) DEFAULT NULL COMMENT 'PC 썸네일 원본 파일명',
  MODIFY `pc_up_name` varchar(255) DEFAULT NULL COMMENT 'PC 썸네일 저장 경로',
  MODIFY `pc_file_ext` varchar(20) DEFAULT NULL COMMENT 'PC 썸네일 확장자',
  MODIFY `pc_file_size` bigint(20) unsigned DEFAULT NULL COMMENT 'PC 썸네일 파일 크기 (byte)',
  MODIFY `fo_ori_name` varchar(255) DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 원본 파일명',
  MODIFY `fo_up_name` varchar(255) DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 저장 경로',
  MODIFY `fo_file_ext` varchar(20) DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 확장자',
  MODIFY `fo_file_size` bigint(20) unsigned DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 파일 크기 (byte)',
  MODIFY `mo_ori_name` varchar(255) DEFAULT NULL COMMENT '모바일 썸네일 원본 파일명',
  MODIFY `mo_up_name` varchar(255) DEFAULT NULL COMMENT '모바일 썸네일 저장 경로',
  MODIFY `mo_file_ext` varchar(20) DEFAULT NULL COMMENT '모바일 썸네일 확장자',
  MODIFY `mo_file_size` bigint(20) unsigned DEFAULT NULL COMMENT '모바일 썸네일 파일 크기 (byte)',
  MODIFY `contents_en` text DEFAULT NULL COMMENT '소개글 (영어)',
  MODIFY `contents_vn` text DEFAULT NULL COMMENT '소개글 (베트남어)',
  MODIFY `home_recommend` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '홈 노출 (0: 미노출, 1: 노출)',
  MODIFY `portfolio_recommend` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '도입사례 화면 노출 (0: 미노출, 1: 노출)',
  COMMENT = '도입사례(도입현황)';

ALTER TABLE `portfolio_files`
  MODIFY `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `portfolio_idx` int(10) unsigned NOT NULL COMMENT '도입사례 (portfolio.idx)',
  MODIFY `orderby` int(10) unsigned DEFAULT NULL COMMENT '정렬순서',
  COMMENT = '도입사례 이미지';

ALTER TABLE `product`
  MODIFY `partner_idx` int(10) unsigned NOT NULL COMMENT '공급 파트너 (partner.idx)',
  MODIFY `forms_idx` int(10) unsigned NOT NULL COMMENT '카테고리 (form_types.idx)',
  MODIFY `congbo` tinyint(2) DEFAULT 0 COMMENT 'CongBo 등록 상품 (0/1)',
  MODIFY `new` tinyint(2) DEFAULT 0 COMMENT '신상품 (0/1, 홈페이지 신상품 · RIPS 테스트베드 신청 대상)',
  MODIFY `testbed` tinyint(2) DEFAULT 0 COMMENT '테스트베드 상품 (0/1)',
  MODIFY `congbo_number` varchar(100) DEFAULT NULL COMMENT 'CongBo 등록번호',
  MODIFY `product_name` varchar(200) NOT NULL COMMENT '제품명 (한국어)',
  MODIFY `product_name_en` varchar(500) NOT NULL DEFAULT '' COMMENT '제품명 (영어)',
  MODIFY `product_name_vn` varchar(200) NOT NULL DEFAULT '' COMMENT '제품명 (베트남어)',
  MODIFY `pc_ori_name` varchar(255) DEFAULT NULL COMMENT 'PC 썸네일 원본 파일명',
  MODIFY `pc_up_name` varchar(255) DEFAULT NULL COMMENT 'PC 썸네일 저장 경로',
  MODIFY `pc_file_ext` varchar(20) DEFAULT NULL COMMENT 'PC 썸네일 확장자',
  MODIFY `pc_file_size` bigint(20) unsigned DEFAULT NULL COMMENT 'PC 썸네일 파일 크기 (byte)',
  MODIFY `fo_ori_name` varchar(255) DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 원본 파일명',
  MODIFY `fo_up_name` varchar(255) NOT NULL COMMENT '폴드(태블릿) 썸네일 저장 경로',
  MODIFY `fo_file_ext` varchar(20) DEFAULT NULL COMMENT '폴드(태블릿) 썸네일 확장자',
  MODIFY `fo_file_size` bigint(20) unsigned NOT NULL COMMENT '폴드(태블릿) 썸네일 파일 크기 (byte)',
  MODIFY `mo_ori_name` varchar(255) DEFAULT NULL COMMENT '모바일 썸네일 원본 파일명',
  MODIFY `mo_up_name` varchar(255) DEFAULT NULL COMMENT '모바일 썸네일 저장 경로',
  MODIFY `mo_file_ext` varchar(20) DEFAULT NULL COMMENT '모바일 썸네일 확장자',
  MODIFY `mo_file_size` bigint(20) unsigned DEFAULT NULL COMMENT '모바일 썸네일 파일 크기 (byte)',
  MODIFY `contents_en` text DEFAULT NULL COMMENT '소개글 (영어)',
  MODIFY `contents_vn` text DEFAULT NULL COMMENT '소개글 (베트남어)',
  MODIFY `home_recommend` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '홈 노출 (0: 미노출, 1: 노출)',
  MODIFY `product_recommend` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '제품 화면 노출 (0: 미노출, 1: 노출)',
  COMMENT = '리치포트 제품 (CongBo · 신상품 · 테스트베드, RIPS 발주 기본금액)';

ALTER TABLE `product_files`
  MODIFY `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
  MODIFY `product_idx` int(10) unsigned NOT NULL COMMENT '제품 (product.idx)',
  MODIFY `orderby` int(10) unsigned DEFAULT NULL COMMENT '정렬순서',
  COMMENT = '리치포트 제품 이미지';

ALTER TABLE `rips_category`
  MODIFY `created_at` datetime DEFAULT current_timestamp() COMMENT '등록일시',
  MODIFY `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT '수정일시',
  COMMENT = 'RIPS 업체 상품 카테고리 (1~4단계)';

ALTER TABLE `rips_inquire`
  COMMENT = 'RIPS 이용신청';

ALTER TABLE `rips_inquire_types`
  MODIFY `subject_en` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '구분명 (영어)',
  MODIFY `subject_vn` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '구분명 (베트남어)',
  COMMENT = 'RIPS 이용신청 업체구분';

ALTER TABLE `rips_order`
  MODIFY `created_at` datetime DEFAULT current_timestamp() COMMENT '등록일시',
  MODIFY `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '등록자',
  MODIFY `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT '수정일시',
  MODIFY `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '수정자',
  COMMENT = 'RIPS 발주';

ALTER TABLE `rips_order_item`
  COMMENT = 'RIPS 발주 상품';

ALTER TABLE `rips_order_log`
  COMMENT = 'RIPS 발주 상태 변경 이력';

ALTER TABLE `rips_product`
  MODIFY `created_at` datetime DEFAULT current_timestamp() COMMENT '등록일시',
  MODIFY `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '등록자',
  MODIFY `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT '수정일시',
  MODIFY `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '수정자',
  COMMENT = 'RIPS 업체 상품';

ALTER TABLE `rips_sales`
  MODIFY `created_at` datetime DEFAULT current_timestamp() COMMENT '등록일시',
  MODIFY `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '등록자',
  MODIFY `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT '수정일시',
  MODIFY `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '수정자',
  COMMENT = 'RIPS 판매 데이터 (일별합계 · 건별 · 재고등록)';

ALTER TABLE `rips_sales_setting`
  MODIFY `created_at` datetime DEFAULT current_timestamp() COMMENT '등록일시',
  MODIFY `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT '수정일시',
  COMMENT = 'RIPS 판매 데이터 API 연동 설정';

ALTER TABLE `sessions`
  MODIFY `id` varchar(255) NOT NULL COMMENT '세션 ID (PK)',
  MODIFY `user_id` bigint(20) unsigned DEFAULT NULL COMMENT '로그인 사용자 (Laravel 기본 users, 미사용)',
  MODIFY `ip_address` varchar(45) DEFAULT NULL COMMENT '접속 IP',
  MODIFY `user_agent` text DEFAULT NULL COMMENT '브라우저 정보',
  MODIFY `payload` longtext NOT NULL COMMENT '세션 데이터 (암호화)',
  MODIFY `last_activity` int(11) NOT NULL COMMENT '마지막 활동 시각 (Unix time)',
  COMMENT = '로그인 세션 (Laravel)';

ALTER TABLE `testbed_inquire`
  COMMENT = '테스트베드 신청 (홈페이지 / RIPS 업체)';

ALTER TABLE `types`
  MODIFY `subject` varchar(200) NOT NULL COMMENT '유형명 (한국어)',
  MODIFY `subject_en` varchar(500) NOT NULL COMMENT '유형명 (영어)',
  MODIFY `subject_vn` varchar(500) NOT NULL COMMENT '유형명 (베트남어)',
  COMMENT = '파트너 · 도입사례 유형 (대형마트, 편의점 등)';
