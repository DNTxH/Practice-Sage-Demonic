<?php
namespace vale\sage\demonic\entitys\types;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\Glass;
use pocketmine\block\Opaque;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\entity\projectile\Throwable;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Facing;
use pocketmine\math\RayTraceResult;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\world\particle\EndermanTeleportParticle;
use pocketmine\world\Position;
use pocketmine\world\sound\EndermanTeleportSound;


/**
 * Class CustomEnderPearlEntity
 * @package vale\sage\demonic\entitys\types
 * @author Jibix
 * @date 08.01.2022 - 23:44
 * @project Genesis
 */
class CustomEnderPearlEntity extends Throwable{

    const BUG_BLOCK_IDS = [
        BlockLegacyIds::WOODEN_SLAB,
        BlockLegacyIds::STONE_SLAB,
        BlockLegacyIds::STONE_SLAB2,
        BlockLegacyIds::STONE_SLAB3,
        BlockLegacyIds::STONE_SLAB4,

        BlockLegacyIds::ACACIA_STAIRS,
        BlockLegacyIds::ANDESITE_STAIRS,
        BlockLegacyIds::STONE_BRICK_STAIRS,
        BlockLegacyIds::BIRCH_STAIRS,
        BlockLegacyIds::COBBLESTONE_STAIRS,
        BlockLegacyIds::DARK_OAK_STAIRS,
        BlockLegacyIds::SANDSTONE_STAIRS,
        BlockLegacyIds::WOODEN_STAIRS,
        BlockLegacyIds::RED_SANDSTONE_STAIRS,
        BlockLegacyIds::RED_NETHER_BRICK_STAIRS,
        BlockLegacyIds::PURPUR_STAIRS,
        BlockLegacyIds::QUARTZ_STAIRS,
        BlockLegacyIds::PRISMARINE_STAIRS,
        BlockLegacyIds::SPRUCE_STAIRS,
        BlockLegacyIds::POLISHED_ANDESITE_STAIRS,
        BlockLegacyIds::SMOOTH_SANDSTONE_STAIRS,
        BlockLegacyIds::POLISHED_DIORITE_STAIRS,
        BlockLegacyIds::POLISHED_GRANITE_STAIRS,
        BlockLegacyIds::SMOOTH_RED_SANDSTONE_STAIRS,
        BlockLegacyIds::OAK_STAIRS,
        BlockLegacyIds::SMOOTH_QUARTZ_STAIRS,
        BlockLegacyIds::NORMAL_STONE_STAIRS,
        BlockLegacyIds::NETHER_BRICK_STAIRS,
        BlockLegacyIds::MOSSY_COBBLESTONE_STAIRS,
        BlockLegacyIds::MOSSY_STONE_BRICK_STAIRS,
        BlockLegacyIds::PRISMARINE_BRICKS_STAIRS,
        BlockLegacyIds::JUNGLE_STAIRS,
        BlockLegacyIds::GRANITE_STAIRS,
        BlockLegacyIds::END_BRICK_STAIRS,

        BlockLegacyIds::CHEST,
        BlockLegacyIds::ENDER_CHEST,
        BlockLegacyIds::TRAPPED_CHEST,
        BlockLegacyIds::LEVER,

        BlockLegacyIds::COBBLESTONE_WALL,

        BlockLegacyIds::ENCHANTING_TABLE,
        BlockLegacyIds::ANVIL,
        BlockLegacyIds::END_PORTAL_FRAME,
        BlockLegacyIds::BED_BLOCK,
        BlockLegacyIds::LANTERN,
    ];

    public $gravity = 0.027;
    public $drag = 0.01;

    public static function getNetworkTypeId(): string{
        return EntityIds::ENDER_PEARL;
    }

    public function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo(0.25, 0.25);
    }

    protected function onHit(ProjectileHitEvent $event): void{
        $owner = $this->getOwningEntity();
        if ($owner !== null) {
            $hitResult = $event->getRayTraceResult();
            $pos = $hitResult->getHitVector();
            if ($event instanceof ProjectileHitBlockEvent) {
                $blockHit = $event->getBlockHit();
                $playerDistance = $owner->getPosition()->floor();
                $blockDistance = $blockHit->getPosition();
                $blockDistance->y = $playerDistance->getY();
                if (in_array($blockHit->getId(), self::BUG_BLOCK_IDS) && $playerDistance->distance($blockDistance->floor()) <= 2.5) {
                    $i = 0;
                    while ($i < 2 && in_array($blockHit->getSide(Facing::opposite($hitResult->getHitFace()))->getId(), self::BUG_BLOCK_IDS)) {
                        $blockHit = $blockHit->getSide(Facing::opposite($hitResult->getHitFace()));
                        $i++;
                    }
                    $blockHit = $blockHit->getSide(Facing::opposite($hitResult->getHitFace()));
                    $pos = $blockHit->getPosition();
                    if (!$this->checkBlock($blockHit)) $pos = $hitResult->getHitVector();
                }
            }

            if ($pos->__toString() == $hitResult->getHitVector()->__toString()) {
                $pos = $this->getWorld()->getBlock($pos)->getSide($hitResult->getHitFace())->getPosition();
            }
            $block = $pos->getWorld()->getBlock($pos);
            $block2 = $pos->getWorld()->getBlock($pos->floor()->subtract(0, 1, 0));
            $block3 = $pos->getWorld()->getBlock($pos->floor()->add(0, 1, 0));
            if (!$this->checkBlock($block) || (!$this->checkBlock($block2) && !$this->checkBlock($block3))) {
                $owner->sendPopup("§cUnsafe teleport, your pearl was reserved!");
                if (!$owner->isCreative() && !$owner->isSpectator()) $owner->getInventory()->addItem(VanillaItems::ENDER_PEARL());
                $this->flagForDespawn();
                return;
            }
            $this->getWorld()->addParticle($origin = $owner->getPosition(), new EndermanTeleportParticle());
            $this->getWorld()->addSound($origin, new EndermanTeleportSound());
            #$target = $event->getRayTraceResult()->getHitVector();
            $owner->setPosition($pos);
            $owner->broadcastMovement(true);
            if($owner instanceof Player){
                $owner->getNetworkSession()->syncMovement($location = $owner->getLocation(), $location->yaw, $location->pitch);
            }
            $this->getWorld()->addSound($pos, new EndermanTeleportSound());
            $owner->attack(new EntityDamageEvent($owner, EntityDamageEvent::CAUSE_FALL, 5));
            $this->flagForDespawn();
        }
    }

    private function checkBlock(Block $block): bool{
        return !($block instanceof Opaque && !$block instanceof Slab && !$block instanceof Stair) || $block instanceof Glass;
    }
}