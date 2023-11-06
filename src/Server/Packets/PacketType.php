<?php

namespace CavePHP\Server\Packets;

use CavePHP\Server\Packets\Uniq\UniqPacket;
use ReflectionClass;

class PacketType
{
    private static ?array $maps = null;

    public function __construct(
        public readonly int $id,
        public readonly ClientState $state,
        public readonly string $abstraction
    ) {
    }

    public static function getMappings(): array
    {
        if (self::$maps === null) {
            self::$maps = [];

            $addMap = static function (ClientState $state, int $id, string $className): void {
                if (!isset(self::$maps[$state->name])) {
                    self::$maps[$state->name] = [];
                }

                self::$maps[$state->name][$id] = $className;
            };

            loopClasses(__DIR__, static function (string $className) use ($addMap): void {
                /** @var $className UniqPacket|string */
                $reflection = new ReflectionClass($className);

                if (in_array(UniqPacket::class, $reflection->getInterfaceNames())) {
                    $addMap(($className)::getState(), ($className)::getId(), $className);
                }
            });
        }

        return self::$maps;
    }

    public static function createFromIdAndStatus(int $id, ClientState $status): PacketType
    {
        foreach (self::getMappings()[$status->name] ?? [] as $packetId => $className) {
            if ($packetId === $id) {
                return new self($packetId, $status, $className);
            }
        }

        return new self($id, $status, GenericPacket::class);
    }
}
