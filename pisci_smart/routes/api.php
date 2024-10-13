<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PisciculteurController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\NourritureController;
use App\Http\Controllers\VisiteurController;
use App\Http\Controllers\DispositifController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BassinController;
use App\Http\Controllers\TypeDemandeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatistiquesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CommentaireController;
use App\Models\Dispositif;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\PerteController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\RapportController;

// Test
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});




Route::get('rapport/cycle/{idCycle}', [RapportController::class, 'generateReport']);
Route::get('dispositif/qrcode/{idDispo}', [DispositifController::class, 'generateQrCode']);
route::get('/dispositif/location/{idDispo}', [DispositifController::class, 'getLocationByDispoId']);




//picsciculteur
Route::get('/pisciculteur', [PisciculteurController::class, 'getAllPisciculteur']);
Route::get('/pisciculteur/{id}', [PisciculteurController::class, 'getPisciculteurById']);
Route::post('/pisciculteur', [PisciculteurController::class, 'create_pisciculteur']);
Route::put('/pisciculteur/{id}', [PisciculteurController::class, 'update_pisciculteur']);
Route::delete('/pisciculteur/{id}', [PisciculteurController::class, 'delete_pisciculteur']);

//employé
Route::get('/employe', [EmployeController::class, 'get_all_employe']);
Route::get('/employe/{id}', [EmployeController::class, 'getEmployeById']);
Route::post('/employe', [EmployeController::class, 'create_employe']);
Route::put('/employe/{id}', [EmployeController::class, 'update_employe']);
Route::get('/employes/total-par-pisciculteur', [EmployeController::class, 'getTotalEmployesByPisciculteur']);

//nourriture
Route::get('/nourritures', [NourritureController::class, 'get_all_nourriture']);
Route::get('/nourritures/{id}', [NourritureController::class, 'getNourritureById']);
Route::post('/nourritures', [NourritureController::class, 'create_nourriture']);
Route::put('/nourritures/{id}', [NourritureController::class, 'update_nourriture']);
Route::delete('/nourritures/{id}', [NourritureController::class, 'delete_nourriture']);


//visiteur
Route::get('/visiteur', [VisiteurController::class, 'get_all_visiteur']);
Route::get('/visiteur/{id}', [VisiteurController::class, 'getVisiteurById']);
Route::post('/visiteur', [VisiteurController::class, 'create_visiteur']);
Route::put('/visiteur/{id}', [VisiteurController::class, 'update_visiteur']);
Route::delete('/visiteur/{id}', [VisiteurController::class, 'delete_visiteur']);


//dispositif
Route::get('/dispositif', [DispositifController::class, 'get_all_dispositif']);
Route::get('/dispositif/{id}', [DispositifController::class, 'getDispositifById']);

Route::post('/dispositif', [DispositifController::class, 'create_dispositif']);
Route::put('/dispositif/{id}', [DispositifController::class, 'update_dispositif']);
Route::delete('/dispositif/{id}', [DispositifController::class, 'delete_dispositif']);
Route::get('/dispositifs/count/{idPisciculteur}', [DispositifController::class, 'count_dispositifs_by_pisciculteur']);
Route::get('/dispositifs/pisciculteur/{idPisciculteur}', [DispositifController::class, 'get_dispositifs_by_pisciculteur']);
Route::get('/dispositifs/count', [DispositifController::class, 'count_all_dispositifs']);

//notification
Route::get('/notification', [NotificationController::class, 'get_all_notification']);
Route::get('/notification/{id}', [NotificationController::class, 'getNotificationById']);
Route::post('/notification', [NotificationController::class, 'create_notification']);
Route::put('/notification/{id}', [NotificationController::class, 'update_notification']);
Route::delete('/notification/{id}', [NotificationController::class, 'delete_notification']);


//bassin
Route::get('/bassin', [BassinController::class, 'get_all_bassin']);
Route::get('/bassin/{id}', [BassinController::class, 'getBassinById']);
Route::post('/bassin', [BassinController::class, 'create_bassin']);
Route::put('/bassin/{id}', [BassinController::class, 'update_Bassin']);
Route::delete('/bassin/{id}', [BassinController::class, 'delete_Bassin']);

//cycle,vente,depense,perte
// Route::middleware('auth:sanctum')->group(function () {
Route::get('/bassins/{idBassin}/cycles', [CycleController::class, 'getCyclesByBassin']);

Route::apiResource('cycles', CycleController::class);
// });
Route::get('/cycles/{idCycle}/ventes', [VenteController::class, 'getVentesByCycle']);
Route::get('/cycles/{idCycle}/depenses', [DepenseController::class, 'getDepensesByCycle']);
Route::get('/cyclesE', [CycleController::class, 'getActiveCycles']);

Route::delete('/cycles/{id}', [CycleController::class, 'destroy']);




Route::apiResource('depenses', DepenseController::class);
Route::apiResource('ventes', VenteController::class);
Route::apiResource('pertes', PerteController::class);
// ->only(['index','show', 'store','update',])

// type demande
Route::post('/type-demandes', [TypeDemandeController::class, 'store']);
Route::get('/type-demandes', [TypeDemandeController::class, 'index']); // Récupérer tous les types de demande


// Routes pour les commentaires
Route::get('/commentaires', [CommentaireController::class, 'index']);
Route::get('/commentaires/{idCommentaire}', [CommentaireController::class, 'show']);
Route::post('/commentaires', [CommentaireController::class, 'store']);
Route::put('/commentaires/{idCommentaire}', [CommentaireController::class, 'update']);
Route::delete('/commentaires/{idCommentaire}', [CommentaireController::class, 'destroy']);
Route::get('total-commentaires', [CommentaireController::class, 'getTotalCommentaires']);



// Route pour like
//Route::post('/like/toggle/{postId}', [LikeController::class, 'toggleLike']);
Route::get('/likes', [LikeController::class, 'index']);
Route::get('/likes/total', [LikeController::class, 'getTotalLikes']);
Route::post('/like/{postId}', [LikeController::class, 'toggleLike']);



//Route pour medias
Route::post('medias', [MediaController::class, 'store']);
Route::get('posts/{postId}/medias', [MediaController::class, 'index']);
Route::get('medias/{idMedia}', [MediaController::class, 'show']);
Route::delete('medias/{idMedia}', [MediaController::class, 'destroy']);

//message
Route::delete('/messages/{id}', [MessageController::class, 'deleteMessage']);
Route::post('messages', [MessageController::class, 'store']);
Route::get('/messages', [MessageController::class, 'index']);
Route::get('/messages/destinataire', [MessageController::class, 'getMessagesByDestinataire']);
Route::get('/messages/expediteur', [MessageController::class, 'getMessagesByExpediteur']);
Route::get('/messages/count/{id}', [MessageController::class, 'countMessagesByDestinataire']);
Route::patch('/messages/{id}/mark-as-read', [MessageController::class, 'markAsRead']);
Route::get('/messages/unread/{destinataireId}', [MessageController::class, 'getUnreadMessages']);


//post
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/filter-by-type', [PostController::class, 'filterByType']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/user', [PostController::class, 'getPostsByUser']);
Route::delete('/posts/{id}', [PostController::class, 'deletePost']);



// connexion,inscription,authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


// statistiques (pisciculteurs,employe, visiteurs)
Route::get('/statistiques-utilisateurs', [StatistiquesController::class, 'getStatistiquesUtilisateurs']);
//details par utilisateurs
Route::get('/details-utilisateurs', [StatistiquesController::class, 'getDetailsUtilisateurs']);



// desactiver et activer compte
Route::post('/admin/desactiver-compte/{id}/{type}', [AdminController::class, 'desactiverCompte']);
Route::post('/admin/activer-compte/{id}/{type}', [AdminController::class, 'activerCompte']);


Route::get('/notifications/pisciculteur/{idPisciculteur}', [CycleController::class, 'getPisciculteurNotifications']);
Route::get('/notifications/employe/{idEmploye}', [CycleController::class, 'getEmployeNotifications']);
Route::post('/check-cycles', [CycleController::class, 'checkCycleEndDate']);

// Route API pour calculer les totaux des ventes, dépenses et bénéfice


Route::get('/total-depenses/{id}', [CycleController::class, 'getTotalDepenses']);
Route::get('/total-ventes/{id}', [CycleController::class, 'getTotalVentes']);
Route::get('/cycle/{id}/benefice', [CycleController::class, 'getBenefice']);

//poisson mort
Route::post('/cycles/{id}/poissons-morts', [CycleController::class, 'addPoissonsMorts']);


Route::post('/dispositifs', [DispositifController::class, 'ajouterDispositif'])->middleware('auth:sanctum');
Route::get('/pisciculteur/dispositifs', [DispositifController::class, 'getDispositifsParPisciculteur'])->middleware('auth:sanctum');

Route::get('/pisciculteur/{idPisciculteur}/dispositifs/numero-serie', [DispositifController::class, 'getDispositifsByPisciculteur']);


// Route pour récupérer les informations des employés d'un pisciculteur spécifique
Route::get('/employes-info/{idPisciculteur}', [EmployeController::class, 'getEmployeInfoByPisciculteur']);

Route::get('/users', [AuthController::class, 'getAllUsers']);

//nombre de perte
Route::put('/cycles/{id}/pertes', [CycleController::class, 'updateNombrePertes']);

//detail cycle
Route::get('/cycles/{id}/details', [CycleController::class, 'getCycleDetails']);

Route::delete('/cycles', [CycleController::class, 'destroyAll']);


//Route::delete('/cycles/{id}', [CycleController::class, 'destroy']);

//bassin d'un pisciculteur
Route::get('bassins/pisciculteur/{idPisciculteur}', [BassinController::class, 'getBassinsByPisciculteur']);

//nbre de dispositif par pisciculteur
Route::get('/pisciculteur/{idPisciculteur}/dispositifs/count', [DispositifController::class, 'countDispositifsByPisciculteur']);


//ajouter poisson mort
Route::post('/cycle/{idCycle}/poissons-morts', [CycleController::class, 'addPoissonsMorts']);

//liste des employés par pisciculteur
Route::get('employes/pisciculteur/{id}', [EmployeController::class, 'get_employes_by_pisciculteur']);

//ajouter employe
Route::post('/employe', [EmployeController::class, 'create_employe']);

//supprimer employe
Route::delete('/employe/{id}', [EmployeController::class, 'delete_employe']);



