<?php

namespace App\Sites\Sections\Tags;

use App\Shared\Helpers;
use App\Shared\Storage;
use App\Sites\Sections\Entries\SectionEntriesDataService;


/**
 * This class is a service that handles site tags (subsections) data for Berta CMS.
 * Tags are stored in `tags.xml` file for the corresponding site.
 *
 * The root site has its tags stored in `storage/tags.xml`,
 * any other site has it's tags in `storage/-sites/[site name]/tags.xml`
 *
 * @example an example of XML file:
 * ```xml
 * <?xml version="1.0" encoding="utf-8"?>
 * <sections>
 *     <section name="section-1" entry_count="3">
 *         <tag name="one" entry_count="2"><![CDATA[One]]></tag>
 *         <tag name="two" entry_count="1"><![CDATA[Two]]></tag>
 *     </section>
 *     <section name="section-2" entry_count="1">
 *         <tag name="three" entry_count="1"><![CDATA[Three]]></tag>
 *     </section>
 * </sections>
 * ```
 */
class SectionTagsDataService Extends Storage {
    /**
     * @var array $JSON_SCHEMA
     * Associative array representing data structure handled by this service.
     */
    public static $JSON_SCHEMA = [
        '$schema' => "http://json-schema.org/draft-06/schema#",
        'type' => 'object',
        'properties' => [
            'section' => [  // <section>
                'type' => 'array',
                '$comment' => 'A list of <section> tags',
                'items' => [

                    'type' => 'object',
                    '$comment' => 'Object representing single <section> tag',
                    'properties' => [
                        'tag' => [

                            'type' => 'array',
                            '$comment' => 'A list of <tag> tags',
                            'items' => [

                                'type' => 'object',
                                '$comment' => 'Object representing single <tag> tag',
                                'properties' => [

                                    '@value' => ['type' => 'string'],
                                    '@attributes' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'name' => ['type' => 'string'],
                                            'entry_count' => [
                                                'type' => 'integer',
                                                'minimum' => 0
                                            ]
                                        ],
                                        'required' => ['name', 'entry_count']
                                    ]
                                ]
                            ]
                        ],
                        '@attributes' => [
                            'type' => 'object',
                            'properties' => [
                                'name' => ['type' => 'string'],
                                'entry_count' => [
                                    'type' => 'integer',
                                    'minimum' => 0
                                ]
                            ],
                            'required' => ['name', 'entry_count']
                        ]
                    ]
                ]
            ],
        ]
    ];
    protected static $DEFAULT_VALUES = [
        'section' => [
            [
                'tag' => [
                    [
                        '@attributes' => [
                            'name' => '',
                            'entry_count' => 0
                        ]
                    ]
                ],
                '@attributes' => [
                    'name' => '',
                    'entry_count' => 0,
                ]
            ]
        ]
    ];
    private $ROOT_ELEMENT = 'sections';
    private $SECTION_NAME;
    private $XML_ROOT;
    private $XML_FILE;
    private $TAGS;

    public function __construct($site='', $sectionName='') {
        parent::__construct($site);
        $this->XML_ROOT = $this->getSiteXmlRoot($site);
        $this->SECTION_NAME = $sectionName;
        $this->XML_FILE = $this->XML_ROOT . '/tags.xml';
    }

    /**
    * Returns all tags of a given site as an array
    *
    * @param string $site name of the site
    * @return array Array of tags
    */
    public function get() {
        if (empty($this->TAGS)) {
            $this->TAGS = $this->xmlFile2array($this->XML_FILE);

            if (empty($this->TAGS)) {
                $this->TAGS = array(
                    'section' => array()
                );
            } else {
                $this->TAGS['section'] = $this->asList($this->TAGS['section']);
            }
        }

        return $this->TAGS;
    }

    /**
     * Returns all tags of a given section
     *
     * @return array Array of tags
     */
    public function getSectionTags() {
        $tags = $this->get();

        if (empty($this->SECTION_NAME)) {
            return null;
        }


        $key = array_search(
            $this->SECTION_NAME,
            array_column(
                array_column(
                    $tags['section'],
                    '@attributes'
                ),
                'name'
                )
        );

        if ($key === false) {
            return null;
        }

        return $tags['section'][$key];
    }

    /**
    */
    public function delete() {
        $tags = $this->get();
        $tags_idx = array_search(
            $this->SECTION_NAME,
            array_column(
                array_column(
                    $tags['section'],
                    '@attributes'
                ),
                'name'
            )
        );

        if ($tags_idx !== False) {
            $section_tags = array_splice($tags['section'], $tags_idx, 1);
            $this->array2xmlFile($tags, $this->XML_FILE, $this->ROOT_ELEMENT);
            return $section_tags;
        }

        return array();
    }

    /**
    */
    public function populateTags() {
        // @@@:TODO: Maybe it's possibe to write this method
        //           in a shorter and/or more efficient way
        $tags = $this->get();
        $entries = new SectionEntriesDataService($this->SITE, $this->SECTION_NAME);
        $blog = $entries->get();

        $newCache = array();
        $allHaveTags = true;
        $section_entry_count = 0;

        if (isset($blog['entry']) && !empty($blog['entry'])) {
            foreach($blog['entry'] as $key => $entry) {
                if($key === '@attributes') { continue; }

                $hasTags = false;

                if(isset($entry['tags'])) {
                    $_tags = $this->asList($entry['tags']['tag']);

                    foreach($_tags as $tag) {
                        $tag_name = trim((string) $tag);

                        if ($tag_name) {
                            $tag_name = Helpers::slugify($tag_name, '-', '-');
                            $c = isset($newCache[$tag_name]) ? $newCache[$tag_name]['@attributes']['entry_count'] : 0;
                            $newCache[$tag_name] = array(
                                '@value' => $tag,
                                '@attributes' => array(
                                    'name' => $tag_name,
                                    'entry_count' => ++$c
                                )
                            );
                            $section_entry_count++;
                            $hasTags = true;
                        }
                    }
                }

                $allHaveTags &= $hasTags;
            }
        }

        $section_idx = array_search(
            $this->SECTION_NAME,
            array_column(
                array_column(
                    $tags['section'],
                    '@attributes'
                ),
                'name'
                )
            );

        //to keep sorting order, we need to check old and new tag arrays
        //loop through old and check if exists and update, else do not add
        $tempCache = array();

        if ($section_idx !== false) {
            foreach ($tags['section'][$section_idx]['tag'] as $tag) {
                $tag_name = $tag['@attributes']['name'];
                if (isset($newCache[$tag_name])){
                    $tempCache[$tag_name] = $newCache[$tag_name];
                }
            }
        }

        //loop through new and check if exists, if not - add at bottom
        foreach ($newCache as $tag => $tagVars) {
            if ($section_idx !== false) {
                $tag_idx = array_search(
                    $tag,
                    array_column(
                        array_column(
                            $tags['section'][$section_idx]['tag'],
                            '@attributes'
                        ),
                        'name'
                    )
                );

                if ($tag_idx === false) {
                    $tempCache[$tag] = $tagVars;
                }
            } else {
                $tempCache[$tag] = $tagVars;
            }
        }

        if ($section_idx !== false) {
            $new_tags = array_values($tempCache);
            $tags['section'][$section_idx]['tag'] = $new_tags;
        } elseif (count($tempCache)) {
            $section_idx = count($tags['section']);
            $new_tags = array(
                'tag' => array_values($tempCache),
                '@attributes' => array(
                    'name' => $this->SECTION_NAME,
                    'entry_count' => $section_entry_count
                )
            );
            $tags['section'][] = $new_tags;
        }

        $this->array2xmlFile($tags, $this->XML_FILE, $this->ROOT_ELEMENT);

        return array(
            'tags' => isset($tags['section'][$section_idx]) ? $tags['section'][$section_idx] : [],
            'allHaveTags' => $allHaveTags
        );
    }

    public function renameSection($new_name) {
        $tags = $this->get();
        $section_idx = array_search(
            $this->SECTION_NAME,
            array_column(
                array_column(
                    $tags['section'],
                    '@attributes'
                ),
                'name'
            )
        );

        if ($section_idx !== false) {
            $tags['section'][$section_idx]['@attributes']['name'] = $new_name;
            $this->array2xmlFile($tags, $this->XML_FILE, $this->ROOT_ELEMENT);
        }
    }
}
