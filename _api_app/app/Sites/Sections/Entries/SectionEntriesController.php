<?php

namespace App\Sites\Sections\Entries;

use Illuminate\Http\Request;
use App\Shared\Storage;
use App\Http\Controllers\Controller;

use App\Sites\Sections\Entries\SectionEntriesDataService;
use App\Sites\Sections\Entries\SectionEntryRenderService;
use App\Sites\Sections\SiteSectionsDataService;
use App\Sites\Settings\SiteSettingsDataService;
use App\Sites\TemplateSettings\SiteTemplateSettingsDataService;

class SectionEntriesController extends Controller
{

    public function create(Request $request)
    {
        $json = $request->json()->all();
        $sectionEntriesDataService = new SectionEntriesDataService($json['site'], $json['section']);
        $res = $sectionEntriesDataService->createEntry($json['before_entry'], $json['tag']);

        return response()->json($res);
    }

    public function update(Request $request)
    {
        $json = $request->json()->all();
        $path_arr = explode('/', $json['path']);
        $site = $path_arr[0];
        $sectionName = $path_arr[2];
        $sectionEntriesDataService = new SectionEntriesDataService($site, $sectionName);
        $res = $sectionEntriesDataService->saveValueByPath($json['path'], $json['value']);

        // @@@:TODO: Replace this with something sensible, when migration to redux is done
        $res['update'] = $res['value'];
        // @@@:TODO:END

        return response()->json($res);
    }

    public function order(Request $request)
    {
        $json = $request->json()->all();
        $sectionEntriesDataService = new SectionEntriesDataService($json['site'], $json['section']);
        $res = $sectionEntriesDataService->order($json['entryId'], $json['value']);
        return response()->json($res);
    }

    public function delete(Request $request)
    {
        $json = $request->json()->all();
        $sectionEntriesDataService = new SectionEntriesDataService($json['site'], $json['section']);
        $res = $sectionEntriesDataService->deleteEntry($json['entryId']);
        return response()->json($res);
    }

    public function galleryOrder(Request $request)
    {
        $json = $request->json()->all();
        $sectionEntriesDataService = new SectionEntriesDataService($json['site'], $json['section']);
        $ret = $sectionEntriesDataService->galleryOrder($json['section'], $json['entryId'], $json['files']);
        return response()->json($ret);
    }

    public function galleryDelete(Request $request)
    {
        $json = $request->json()->all();
        $sectionEntriesDataService = new SectionEntriesDataService($json['site'], $json['section']);
        $ret = $sectionEntriesDataService->galleryDelete($json['section'], $json['entryId'], $json['file']);
        return response()->json($ret);
    }

    /**
     * This method is entry rendering example
     */
    public function renderEntries($site, $section, $id=null, Request $request) {
        $sectionEntriesDS = new SectionEntriesDataService($site, $section);
        $siteSectionsDS = new SiteSectionsDataService($site);
        $siteSettingsDS = new SiteSettingsDataService($site);
        $siteTemplateSettingsDS = new SiteTemplateSettingsDataService($site);

        $sectionData = $siteSectionsDS->get($section);
        if (!$sectionData) {
            return abort(404, "Section with name {$section} not found!");
        }

        $res = '';
        foreach ($sectionEntriesDS->get()['entry'] as $entry) {
            if ($id !== null && $entry['id'] !== $id) {
                continue;
            }
            $sectionEntriesRS = new SectionEntryRenderService(
                $entry,
                $sectionData,
                $siteSettingsDS->getState(),
                $siteTemplateSettingsDS->getState(),
                (new Storage()),
                false,
                config('plugin-Shop.key') === $request->getHost()
            );
            $res .= $sectionEntriesRS->render();
        }

        if ($res === '' && $id !== null) {
            return abort(404, "Entry with id {$id} not found!");
        }

        return response($res);
    }
}
