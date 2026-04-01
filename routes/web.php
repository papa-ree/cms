<?php

use Bale\Core\Support\Cdn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Bale\Cms\Livewire\Pages\Navigation\CreateNewNavigation;
use Bale\Cms\Livewire\Pages\Navigation\EditNavigation;
use Bale\Cms\Livewire\Pages\Navigation\Index as NavigationIndex;
use Bale\Cms\Livewire\Pages\Post\CreateNewPost;
use Bale\Cms\Livewire\Pages\Post\EditPost;
use Bale\Cms\Livewire\Pages\Overview\Index;
use Bale\Cms\Livewire\Pages\Page\CreateNewPage;
use Bale\Cms\Livewire\Pages\Page\EditPage;
use Bale\Cms\Livewire\Pages\Page\Index as PageIndex;
use Bale\Cms\Livewire\Pages\Post\Index as PostIndex;
use Bale\Cms\Livewire\Pages\Section\CreateNewSection;
use Bale\Cms\Livewire\Pages\Section\EditSection;
use Bale\Cms\Livewire\Pages\Section\Index as SectionIndex;
use Bale\Cms\Livewire\Pages\Section\Section\ExtensionSectionForm;
use Bale\Cms\Livewire\Pages\Section\SectionMetaEditor;
use Bale\Cms\Livewire\Pages\Section\Section\SearchableSectionForm;
use Bale\Cms\Livewire\Pages\Section\Section\SearchableSectionTableView;
use Bale\Cms\Livewire\Pages\Section\Section\SearchableEditKey;
use Bale\Cms\Livewire\Pages\Section\Section\SearchableCreateItem;
use Bale\Cms\Middleware\EnsureBaleSelected;
use Bale\Cms\Middleware\SwitchBaleConnection;

/*
 Note:
 - 'web' and 'auth' already provided by user app.
 - Include our middleware in group so SwitchBaleConnection runs after EnsureBaleSelected.
*/

Route::middleware(['web', 'auth'])->prefix('cms')->as('bale.cms.')->group(function () {

    // protected CMS pages
    Route::middleware([EnsureBaleSelected::class, SwitchBaleConnection::class])->group(function () {
        Route::get('overview', Index::class)->name('overview');

        Route::name('posts.')->middleware('permission:bale-post.read')->group(function () {
            Route::get('posts', PostIndex::class)->name('index');
            Route::get('posts.create', CreateNewPost::class)->name('create');
            Route::get('posts.edit.{slug}', EditPost::class)->name('edit');
        });

        Route::post('/editorjs/upload', function (Request $request) {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|max:2048', // 2MB
            ]);

            if ($validator->fails()) {
                \Illuminate\Support\Facades\Log::warning('EditorJS Upload Validation Failed', [
                    'errors' => $validator->errors()->toArray(),
                    'user_id' => auth()->id()
                ]);

                return response()->json([
                    'success' => 0,
                    'message' => $validator->errors()->first('image'),
                ]);
            }

            try {
                $file = $request->file('image');
                $filename = uniqid() . '.' . $file->extension();
                $prefix = trim(\Bale\Core\Support\Cdn::prefix(), '/');
                $orgSlug = session('bale_active_slug');

                // Final S3 path matching Cdn::url structure
                $path = ($prefix ? $prefix . '/' : '') . $orgSlug . '/images/' . $filename;

                Storage::disk('s3')->put($path, file_get_contents($file));

                // Generate CDN URL
                $url = \Bale\Core\Support\Cdn::url('images/' . $filename);

                return response()->json([
                    'success' => 1,
                    'file' => [
                        'url' => $url,
                    ],
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('EditorJS Upload Error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'user_id' => auth()->id(),
                    'bale_active_slug' => session('bale_active_slug')
                ]);

                return response()->json([
                    'success' => 0,
                    'message' => 'Upload failed: ' . $e->getMessage(),
                ], 500);
            }
        })->name('editorjs.upload');

        Route::post('/editorjs/fetchUrl', function (Request $request) {
            $url = $request->input('url');

            try {
                $contents = file_get_contents($url);
                $filename = uniqid() . '.jpg';
                $prefix = trim(\Bale\Core\Support\Cdn::prefix(), '/');
                $orgSlug = session('bale_active_slug');

                $path = ($prefix ? $prefix . '/' : '') . $orgSlug . '/images/' . $filename;

                Storage::disk('s3')->put($path, $contents);

                // Generate CDN URL
                $cdnUrl = \Bale\Core\Support\Cdn::url('images/' . $filename);

                return response()->json([
                    'success' => 1,
                    'file' => [
                        'url' => $cdnUrl,
                    ],
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('EditorJS FetchUrl Error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'url' => $url,
                    'user_id' => auth()->id(),
                    'bale_active_slug' => session('bale_active_slug')
                ]);

                return response()->json([
                    'success' => 0,
                    'message' => 'Fetch failed: ' . $e->getMessage(),
                ], 500);
            }
        });

        Route::name('pages.')->middleware('permission:bale-page.read')->group(function () {
            Route::get('pages', PageIndex::class)->name('index');
            Route::get('pages.create', CreateNewPage::class)->name('create');
            Route::get('pages.edit.{slug}', EditPage::class)->name('edit');
        });

        Route::name('navigations.')->middleware('permission:bale-navigation.read')->group(function () {
            Route::get('navigations', NavigationIndex::class)->name('index');
            Route::get('navigations.create.{parent?}', CreateNewNavigation::class)->name('create');
            Route::get('navigations.edit.{slug?}', EditNavigation::class)->name('edit');
        });

        Route::name('sections.')->middleware('permission:bale-section.read')->group(function () {
            Route::get('sections', SectionIndex::class)->name('index');
            Route::get('sections.create', CreateNewSection::class)->name('create'); // create new section
            Route::get('sections.meta-editor.{slug}', SectionMetaEditor::class)->name('meta-editor'); // edit section meta
            Route::get('sections.edit-keys.{slug}', SearchableEditKey::class)->name('edit-keys'); // edit field keys
            Route::get('sections.view.items.{slug}', SearchableSectionTableView::class)->name('view-searchable'); // view searchable section data
            Route::get('sections.create-item.{slug}', SearchableCreateItem::class)->name('create-searchable-item'); // create new item
            Route::get('sections.edit-item.{slug}.{itemId}', SearchableCreateItem::class)->name('edit-searchable-item'); // edit existing item
        });

        // Route::name('roles.')->middleware('permission:bale-role.read')->group(function () {
        //     Route::livewire('roles', 'cms-pages::role.index')->name('index');
        //     Route::livewire('roles.create', 'cms-pages::role.create')->name('create');
        //     Route::livewire('roles.edit.{roleId}', 'cms-pages::role.edit')->name('edit');
        // });

        // Categories Management
        Route::name('categories.')->middleware('permission:bale-category.read')->group(function () {
            Route::livewire('categories', 'cms-pages::category.index')->name('index');
            Route::livewire('categories.create', 'cms-pages::category.create')->name('create');
            Route::livewire('categories.edit.{slug}', 'cms-pages::category.create')->name('edit');
        });

        // Route::name('permissions.')->middleware('permission:bale-role.read')->group(function () {
        //     Route::livewire('permissions', 'cms-pages::permission.index')->name('index');
        // });

        Route::name('users.')->middleware('permission:bale-user.read')->group(function () {
            Route::livewire('users', 'cms-pages::user.index')->name('index');
        });

        Route::livewire('exit-cms', 'cms-shared-components::exit-cms')->name('exit-cms');

        Route::post('/editorjs/log', function (Request $request) {
            $message = $request->input('message', 'Unknown JS error');
            $context = $request->input('context', []);
            $level = $request->input('level', 'error');

            if ($level === 'warning') {
                \Illuminate\Support\Facades\Log::warning('EditorJS Frontend Log: ' . $message, $context);
            } else {
                \Illuminate\Support\Facades\Log::error('EditorJS Frontend Log: ' . $message, $context);
            }

            return response()->json(['success' => true]);
        })->name('editorjs.log');

        // add other CMS routes here...
    });
});
