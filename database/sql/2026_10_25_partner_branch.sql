-- 파트너 본점/지점 구분 (2026-10-25)
-- 단일 매장은 본점, 지점은 등록된 본점을 선택해서 등록 (본점은 구분값일 뿐 하위 지점 조회 권한 없음)

ALTER TABLE partner
	ADD COLUMN branch_type VARCHAR(10) NOT NULL DEFAULT 'head' COMMENT '본점/지점(head:본점, branch:지점)' AFTER country,
	ADD COLUMN head_idx INT UNSIGNED NULL COMMENT '소속 본점(partner.idx, 지점일 때만)' AFTER branch_type,
	ADD KEY idx_partner_head (head_idx);
