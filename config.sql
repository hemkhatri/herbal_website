SELECT 
    k.TABLE_NAME, 
    k.COLUMN_NAME, 
    k.CONSTRAINT_NAME, 
    k.REFERENCED_TABLE_NAME, 
    k.REFERENCED_COLUMN_NAME 
FROM 
    information_schema.KEY_COLUMN_USAGE k
WHERE 
    k.TABLE_SCHEMA = 'aushadhi_platform'
ORDER BY 
    k.TABLE_NAME;


SELECT table_name, column_name, data_type 
FROM information_schema.columns 
WHERE table_name IN ('admins', 'delivery_personnel', 'orders', 'products', 'product_images', 'product_ingredients', 'product_reviews', 'product_rituals', 'users');
