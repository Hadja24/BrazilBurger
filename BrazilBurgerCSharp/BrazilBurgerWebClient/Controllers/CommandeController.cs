using BrazilBurgerWebClient.Services.Interfaces;
using Microsoft.AspNetCore.Mvc;

namespace BrazilBurgerWebClient.Controllers
{
    public class CommandeController : Controller
    {
        private readonly ISuiviCommandeService _suiviCommandeService;

        public CommandeController(ISuiviCommandeService suiviCommandeService)
        {
            _suiviCommandeService = suiviCommandeService;
        }

        // 🔐 Liste des commandes du client connecté
        public IActionResult MesCommandes()
        {
            int? clientId = HttpContext.Session.GetInt32("ClientId");

            if (clientId == null)
                return RedirectToAction("Login", "Auth");

            var commandes = _suiviCommandeService.GetCommandesClient(clientId.Value);
            return View(commandes);
        }

        // 🔐 Détails d'une commande
        public IActionResult Details(int id)
        {
            int? clientId = HttpContext.Session.GetInt32("ClientId");

            if (clientId == null)
                return RedirectToAction("Login", "Auth");

            var commande = _suiviCommandeService.GetDetailsCommande(id, clientId.Value);

            if (commande == null)
                return NotFound();

            return View(commande);
        }
    }
}
