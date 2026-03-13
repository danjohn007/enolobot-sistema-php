<?php
class MenuCategory extends Model {
    protected $table = 'menu_categories';

    public function getCategoriesByHotel($hotelId) {
        $sql = "SELECT * FROM {$this->table} WHERE hotel_id = ? AND is_active = 1 ORDER BY display_order, name";
        return $this->db->select($sql, [$hotelId]);
    }

    public function getCategoryWithDishes($hotelId, $categoryId) {
        $category = $this->getById($categoryId);
        if ($category && $category['hotel_id'] == $hotelId) {
            $dishModel = new Dish();
            $category['dishes'] = $dishModel->getDishesByCategory($hotelId, $categoryId);
            return $category;
        }
        return null;
    }
}
