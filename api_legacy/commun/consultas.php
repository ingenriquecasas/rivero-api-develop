<?php
class Conexion extends Database{
       
     public function get_inventario_seminuevos(){
         $conn = Database::connect();
        $sql1="SELECT * FROM inventario WHERE panoramica=1";
        $result1=$conn->query($sql1);
            while ($row = $result1->fetch_assoc()) {
                $out[]=array_map("utf8_encode", $row);
            }
        $conn=Database::close();
        return $out;
    }
     public function get_inventario_nuevos(){
         $conn = Database::connect();
        //$sql1="SELECT * FROM catalogo GROUP BY modelo,ano";
        $sql1="SELECT max(t1.ano) as ano,t1.descripcion,
        t1.fecha,
        t1.id,
        t1.marca,
        t1.marca_id,
        t1.modelo,
        t1.modelo_id,
        min(t1.precio) as precio,
        t1.tipo,
        t1.tipo_vehiculo,
        t1.version_id,
        t2.color as color
        from catalogo t1 left join inventario_colores t2
        on t1.modelo=t2.modelo and t1.ano=t2.ano
        WHERE t2.orden=2 and t1.precio>'80000'
        GROUP BY t1.modelo, t1.ano order by 9 ASC
        ";
        $result1=$conn->query($sql1);
            while ($row = $result1->fetch_assoc()) {
                $out[]=array_map("utf8_encode", $row);
            }
        $conn=Database::close();
        return $out;
    }
    
     public function get_blogs(){
         $conn = Database::connect();
        $sql1='SELECT id,title,descripcion, CONCAT("https://www.gruporivero.com/assets/img/blog/",id,"/portada.png") as imagen FROM blogs order by id desc';
        $result1=$conn->query($sql1);
            while ($row = $result1->fetch_assoc()) {
                $out[]=array_map("utf8_encode", $row);
            }
        $conn=Database::close();
        return $out;
    }
    
    public function get_versiones($param){
        $conn = Database::connect();
        $sql1='SELECT * FROM get_versiones WHERE modelo="'.$param["modelo"].'" and ano="'.$param["year"].'"';
        $result1=$conn->query($sql1);
            while ($row = $result1->fetch_assoc()) {
                $out[]=array_map("utf8_encode", $row);
            }
        $conn=Database::close();
        return $out;
    }
     public function get_colores($param){
        $conn = Database::connect();
        $sql1='SELECT color FROM inventario_colores WHERE modelo="'.$param["modelo"].'" and ano="'.$param["year"].'"';
        $result1=$conn->query($sql1);
            while ($row = $result1->fetch_assoc()) {
                $out[]=array_map("utf8_encode", $row);
            }
        $conn=Database::close();
        return $out;
    }
    
}
?>