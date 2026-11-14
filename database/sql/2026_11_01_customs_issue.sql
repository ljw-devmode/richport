-- RIPS 통관 이슈 (2026-11-01)
-- 관리자 > RIPS 관리 > 통관 이슈 에서 등록, 관리자 대시보드에 최근 5건 표시
-- 베트남에서 발생한 통관 이슈를 한/영/베로 등록 (제목, 내용, 첨부파일 1개)
-- 권한 customsIssue.* 신설 (최고관리자 level 1 은 권한과 무관하게 모두 사용 가능, 그 외는 사용자 관리에서 부여)
-- DB 별로 1회만 실행

CREATE TABLE customs_issue (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	title VARCHAR(500) NOT NULL COMMENT '제목(ko)',
	title_en VARCHAR(500) NOT NULL DEFAULT '' COMMENT '제목(en)',
	title_vn VARCHAR(500) NOT NULL DEFAULT '' COMMENT '제목(vn)',
	contents TEXT NOT NULL COMMENT '내용(ko)',
	contents_en TEXT NULL COMMENT '내용(en)',
	contents_vn TEXT NULL COMMENT '내용(vn)',
	ori_name VARCHAR(255) NULL COMMENT '첨부파일 원본명',
	up_name VARCHAR(255) NULL COMMENT '첨부파일 저장경로 (storage/app/private)',
	file_ext VARCHAR(20) NULL COMMENT '첨부파일 확장자',
	file_size BIGINT UNSIGNED NULL COMMENT '첨부파일 크기',
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '작성일',
	created_by VARCHAR(50) DEFAULT NULL COMMENT '작성자',
	updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
	updated_by VARCHAR(50) DEFAULT NULL COMMENT '수정자',
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS 통관 이슈';
