<?php

// FONCTIONs POUR L'ACCES A LA BASE DE DONNEES
// Ajouter en t�tes 
// Voir : jeu de caract�res � la connection

/** 
 * Se connecte au serveur de donn�es                     
 * Se connecte au serveur de donn�es � partir de valeurs
 * pr�d�finies de connexion (h�te, compte utilisateur et mot de passe). 
 * Retourne l'identifiant de connexion si succ�s obtenu, le bool�en false 
 * si probl�me de connexion.
 * @return resource identifiant de connexion
 */
function connecterServeurBD() 
{
    $PARAM_hote='localhost'; // le chemin vers le serveur
    $PARAM_port='3306';
    $PARAM_nom_bd='baseLafleur1'; // le nom de votre base de donn�es
    $PARAM_utilisateur='root'; // nom d'utilisateur pour se connecter
    $PARAM_mot_passe=''; // mot de passe de l'utilisateur pour se connecter

    $connect = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
 
    return $connect;
}

function lister()
{
    $connexion = connecterServeurBD();
   
    $requete="select * from produit";
    
    $jeuResultat=$connexion->query($requete); // on va chercher tous les membres de la table qu'on trie par ordre croissant
  
    $i = 0;
    $ligne = $jeuResultat->fetch();
    while($ligne)
    {
        $fleur[$i]['image']=$ligne['pdt_image'];
        $fleur[$i]['ref']=$ligne['pdt_ref'];
        $fleur[$i]['designation']=$ligne['pdt_designation'];
        $fleur[$i]['prix']=$ligne['pdt_prix'];
        $ligne=$jeuResultat->fetch();
        $i = $i + 1;
    }
    $jeuResultat->closeCursor();   // fermer le jeu de r�sultat
  
  return $fleur;
}


function ajouter($ref, $des, $prix, $image, $cat,&$tabErr)
{
  // Ouvrir une connexion au serveur mysql en s'identifiant
  $connexion = connecterServeurBD();
    
    // V�rifier que la r�f�rence saisie n'existe pas d�ja
    $requete="select * from produit";
    $requete=$requete." where pdt_ref = '".$ref."';"; 
    $jeuResultat=$connexion->query($requete); // on va chercher tous les membres de la table qu'on trie par ordre croissant
  
    //$jeuResultat->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le r�sultat soit r�cup�rable sous forme d'objet     
    
    $ligne = $jeuResultat->fetch();
    if($ligne)
    {
      $message="Echec de l'ajout : la r�f�rence existe d�j� !";
      ajouterErreur($tabErr, $message);
    }
    else
    {
      // Cr�er la requ�te d'ajout 
       $requete="insert into produit"
       ."(pdt_ref,pdt_designation,pdt_prix,pdt_image, pdt_categorie) values ('"
       .$ref."','"
       .$des."',"
       .$prix.",'"
       .$image."','"
       .$cat."');";
     
        // Lancer la requ�te d'ajout 
        $ok=$connexion->query($requete); // on va chercher tous les membres de la table qu'on trie par ordre croissant
          
        // Si la requ�te a r�ussi
        if ($ok)
        {
          $message = "La fleur a �t� correctement ajout�e";
          ajouterErreur($tabErr, $message);
        }
        else
        {
          $message = "Attention, l'ajout de la fleur a �chou� !!!";
          ajouterErreur($tabErr, $message);
        } 
  
    }
}

?>
