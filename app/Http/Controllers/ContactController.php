<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contact;
use App\DTO\ImportDTO;
use App\Job\AddContactJob;
use App\Job\TrackJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ContactController extends Controller
{
    private const LOCATION = 'app/public/uploads/';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $contact = Contact::Owner(auth()->user())->paginate(20);

        return view('contact.index', compact('contact'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function store(Request $request): void
    {
        $contact = Contact::createContact(
            $request->firstName,
            $request->email,
            $request->phoneNumber,
            auth()->user()->id,
        );

        $contact->save();
        $this->dispatch(new AddContactJob($contact));
    }

    public function create(): View
    {
        return view('contact.create');
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'email' => 'required',
        ]);
        $contact->update($request->all());
        $this->dispatch(new AddContactJob($contact));

        return redirect()->route('contact.index')
            ->with('success', 'Contact updated successfully');
    }

    public function show(Contact $contact): View
    {
        return view('contact.show', compact('contact'));
    }

    public function edit(Contact $contact): View
    {
        return view('contact.edit', compact('contact'));
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('contact.index')
            ->with('success', 'contact deleted successfully');
    }

    public function track(): void
    {
        $this->dispatch(new TrackJob());
    }

    public function import(Request $request): RedirectResponse
    {
        $file = $request->file('file');
        if (!$file->isValid()) {
            return redirect('/contacts')->with('error', 'Error during uploading file');
        }

        $file = $this->setFile($file);
        $records = $this->removeHeaderIfExist($file);

        foreach ($records as $value) {
            $row = explode(',', $value);
            ImportDTO::insertData($row[0], $row[1], $row[2]);
            $contact = Contact::createContact($row[0], $row[1], $row[2], auth()->user()->id);
            $this->dispatch(new AddContactJob($contact));
        }

        return redirect()->route('contact.index')->with('success', 'Added new contacts from csv file');
    }

    private function removeHeaderIfExist(array $file): array
    {
        if (in_array('email', explode(',', $file[0]), true)) {
            return array_slice($file, 1);
        }

        return $file;
    }

    private function setFile($file): array
    {
        $filename = auth()->user()->id . '_' . date('y-m-d-H-i-s') . $file->getClientOriginalName() . '.csv';
        $fileDir = storage_path(self::LOCATION);
        $file->move($fileDir, $filename);

        return file($fileDir . $filename);
    }
}
