<?php

declare(strict_types=1);

namespace app\models\settings;

class ConsultationUserOrganisation implements \JsonSerializable
{
    public string $name;
    public array $autoUserGroups; // No UI exists to set this field yet - needs to be set via database

    public static function fromJson(string|array $orga): self
    {
        $orgaObject = new self();
        if (is_string($orga)) { // Format used up to Antragsgrün v4.12
            $orgaObject->name = $orga;
            $orgaObject->autoUserGroups = [];
        } else {
            $orgaObject->name = $orga['name'];
            $orgaObject->autoUserGroups = $orga['autoUserGroups'];
        }
        return $orgaObject;
    }

    /**
     * @param ConsultationUserOrganisation[] $objects
     * @param string[] $strings
     * @return ConsultationUserOrganisation[]
     */
    public static function mergeObjectWithStringList(array $objects, array $strings): array
    {
        $stringsNormalized = array_map(fn(string $str): string => mb_strtolower($str), $strings);

        // Filter out organisations that should not be in the list anymore
        $keyedList = [];
        foreach ($objects as $object) {
            if (in_array(mb_strtolower($object->name), $stringsNormalized)) {
                $keyedList[mb_strtolower($object->name)] = $object;
            }
        }

        // Add missing organisations
        foreach ($strings as $string) {
            if (!isset($keyedList[mb_strtolower($string)])) {
                $orga = new self();
                $orga->name = $string;
                $orga->autoUserGroups = [];
                $keyedList[mb_strtolower($string)] = $orga;
            }
        }

        ksort($keyedList, SORT_NATURAL | SORT_FLAG_CASE);
        return array_values($keyedList);
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'autoUserGroups' => $this->autoUserGroups,
        ];
    }
}
