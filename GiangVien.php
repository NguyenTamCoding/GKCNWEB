<?php
class GiangVien {
    private $idgv;
    private $fullname;
    private $image;
    private $sumClass;

    public function __construct($idgv, $fullname, $image, $sumClass) {
        $this->idgv = $idgv;
        $this->fullname = $fullname;
        $this->image = $image;
        $this->sumClass = $sumClass;
    }

    public function getIdgv() {
        return $this->idgv;
    }

    public function setIdgv($idgv) {
        $this->idgv = $idgv;
    }

    public function getFullname() {
        return $this->fullname;
    }

    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getSumClass() {
        return $this->sumClass;
    }

    public function setSumClass($sumClass) {
        $this->sumClass = $sumClass;
    }
}
?>