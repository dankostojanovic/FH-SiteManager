SELECT DISTINCT
	h1.Plan_Master as PlanMaster
	,h1.Plan_Name as PlanName
	,b1.MinBedrooms
	,b1.MaxBedrooms
	,b1.MinFullBaths
	,b1.MaxFullBaths
	,b1.MinHalfBaths
	,b1.MaxHalfBaths
	,b1.MinGarage
	,b1.MaxGarage
	,b1.MinSqFt
	,b1.MaxSqFt
	,b1.Stories
	,CASE 
		WHEN (P1.ProductType = 'Single Family Homes') THEN 1
		WHEN (P1.ProductType = 'Maple Street Homes') THEN 1
		WHEN (P1.ProductType = 'Patio Homes') THEN 2
		WHEN (P1.ProductType = 'Condominiums/Townehomes') THEN 3
		ELSE NULL
	END AS 'ProductTypeID'
   	,b1.FirstFloorMaster
	,b1.OwnersRetreat
	,b1.KitchenIsland
	,b1.BonusSpaceLoft
	,b1.SecondWalkInClosets
	,b1.FamilyEntryFoyer
	,b1.Study
	,b1.FormalDiningRoom
	,b1.TwoStoryFamilyRoom
	,b1.GuestSuite
	,b1.SunRoom
	,b1.HearthRoom
	,b1.ProductTypeID
FROM 
	"FISCHER MANAGEMENT".Hometype h1 
	INNER JOIN "FISCHER MANAGEMENT".Basefeatures b1 ON
		h1.Plan_Master = b1.Plan_Master
	INNER JOIN "FISCHER MANAGEMENT".ProductType p1 ON
		b1.ProductTypeID = p1.ProductTypeID		
WHERE
	h1.Plan_Active = 1
;