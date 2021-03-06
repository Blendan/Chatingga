<?php
  class Post
  {
    private $Nachricht = "ERROR: 404! Mesage not Found";
    private $NachrichtID = "ERROR: 404! MesageID not Found";
    private $Verfasser = "ERROR: 404! User not Found";
    private $Zeitpunkt = "ERROR: 404! Timestamp not Found";
    private $Chatraum = "ERROR: 404! Chatraum not Found";

    function __construct($row)
    {
      if(isset($row["NachrichtID"]))
      {
        $this->NachrichtID = $row["NachrichtID"];
      }
      $this->Nachricht = $row["Nachricht"];
      $this->Verfasser = $row["Verfasser"];
      $this->Zeitpunkt = $row["Zeitpunkt"];
      $this->Chatraum = $row["Chatraum"];
    }

    public function addMesage() // schreibt eine Nachricht in dei DB
    {
      $server ="mysql:dbname=chatinga;host=localhost";
      $user="root";
      $password = "";
      $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
      $pdo = new PDO($server,$user,$password,$options);

      $newMesage = array();
      $newMesage["Verfasser"] = $this->Verfasser;
      $newMesage["Nachricht"] = $this->Nachricht;
      $newMesage["Zeitpunkt"] = $this->Zeitpunkt;
      $newMesage["Chatraum"] = $this->Chatraum;

      $stadement = $pdo->prepare("INSERT INTO `nachricht` (`NachrichtID`, `Verfasser`, `Nachricht`, `Zeitpunkt`, `Chatraum`) VALUES (NULL,:Verfasser,:Nachricht,:Zeitpunkt,:Chatraum)");

      try
      {
        $stadement->execute($newMesage);
      }
      catch (PDOException $e)
      {
        return "Insert ERROR:".$e->getNachricht();
      }


    }

    public function getNachricht()
    {
      return $this->Nachricht;
    }

    public function getNachrichtID()
    {
      return $this->NachrichtID;
    }

    public function getVerfasser()
    {
      return $this->Verfasser;
    }

    public function getVerfasserName() //gibt den nemane des verfassers zurück
    {
      $server ="mysql:dbname=chatinga;host=localhost";
      $user="root";
      $password = "";
      $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
      $pdo = new PDO($server,$user,$password,$options);

      try
      {
        $row = $pdo->query("SELECT n.Nutzername FROM nutzer n WHERE NutzerID = ".$this->getVerfasser());
        return $row->fetchColumn();
      }
      catch (PDOException $e)
      {
        return "Insert ERROR:".$e->getNachricht();
      }
    }

    public function getZeitpunkt()
    {
      $date=date_create("$this->Zeitpunkt");
      return date_format($date,"H:i");
    }
  }
 ?>
