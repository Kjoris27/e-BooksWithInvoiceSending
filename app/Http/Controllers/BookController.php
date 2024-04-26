<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use App\Models\Checkout;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('products', compact('books'));
    }

    public function bookCart()
    {
        return view('cart');
    }

    public function addBookToCart($id)
    {
        $book = Book::findOrFail($id);
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $book->name,
                "quantity" => 1,
                "price" => $book->price,
                "image" => $book->image
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Book has been added to cart!');
    }

    public function deleteProduct(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Book successfully deleted.');
        }
    }

    public function checkout()
    {
        // Obtenez le panier depuis la session
        $cart = session()->get('cart', []);

        // Calculez le montant total en sommant les prix des produits
        $totalAmount = collect($cart)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        // Générez le contenu du PDF à partir du panier
        $pdfPath = $this->generateInvoicePDF($cart, $totalAmount);

        // Retournez le PDF comme une réponse pour le téléchargement
        return response()->download($pdfPath, 'invoice.pdf');
    }

    public function processCheckout(Request $request)
    {
        // Valider les données du formulaire (ajoutez des règles de validation selon vos besoins)
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        // Récupérer les informations du panier depuis la session
        $cart = session()->get('cart', []);

        // Générer le contenu du PDF à partir du panier
        $pdfPath = $this->generateInvoicePDF($cart);

        // Enregistrer les informations du checkout dans la base de données
        $checkoutData = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            // Ajoutez d'autres champs au besoin
        ];

        $checkout = Checkout::create($checkoutData);

        // Enregistrer les détails de la commande dans la base de données
        foreach ($cart as $bookId => $item) {
            OrderDetail::create([
                'checkout_id' => $checkout->id,
                'book_id' => $bookId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Effacer le panier après le checkout
        session()->forget('cart');

        // Envoyer l'e-mail avec le PDF attaché
        Mail::to($request->input('email'))
            ->send(new InvoiceMail($pdfPath));

        // Rediriger l'utilisateur avec un message de succès
        return redirect()->route('home')->with('success', 'Checkout successful! Invoice sent to your email.');
    }

    public function showInvoiceForm()
    {
        // Obtenez le chemin du PDF depuis le stockage
        $pdfPath = $this->generateInvoicePDF(session()->get('cart'));

        // Vérifiez si le fichier PDF existe
        if (!file_exists($pdfPath)) {
            return redirect()->route('checkout')->with('error', 'Invoice not generated yet.');
        }

        return view('send_invoice_form', compact('pdfPath'));
    }

    public function sendInvoiceForm()
    {
        return view('send_invoice_form');
    }

    public function sendInvoice(Request $request)
    {
        // Validez les données du formulaire
        $request->validate([
            'email' => 'required|email',
            'pdfPath' => 'required',
        ]);

        // Envoie l'e-mail avec le PDF joint
        Mail::to($request->input('email'))
            ->send(new InvoiceMail($request->input('pdfPath')));

        // Redirige l'utilisateur avec un message de succès
        return redirect()->route('checkout')->with('success', 'Invoice sent by email!');
    }

    private function generateInvoicePDF($cart, $totalAmount = null)
    {
        // Générez le contenu du PDF à partir du panier
        $pdf = PDF::loadView('invoice', compact('cart', 'totalAmount'));

        // Enregistrez le PDF sur le serveur (facultatif)
        $pdfPath = storage_path('app/public/invoice.pdf');
        $pdf->save($pdfPath);

        return $pdfPath;
    }

    public function sendBillingEmail(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'email' => 'required|email',
        ]);

        // Obtenez le chemin du PDF à partir du formulaire
        $pdfPath = $this->generateInvoicePDF(session()->get('cart'));

        // Envoyez l'e-mail avec le PDF en pièce jointe
        Mail::to($request->input('email'))
            ->send(new InvoiceMail($pdfPath));

        // Retournez la réponse après l'envoi de l'e-mail
        return redirect()->route('checkout')->with('success', 'Invoice sent by email!');
    }
}
