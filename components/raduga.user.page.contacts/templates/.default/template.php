<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc; 

$this->setFrameMode(true);

Loc::loadMessages(__FILE__);
//\CJSCore::Init('ajax');

$id=$this->randString();
$mainContainerId = 'owner-main_'.$id;
$injectId = 'owner-main-info-contacts_'.$id;
$ButtunId = 'owner-main__show_'.$id;
$StatusId = 'owner-main-avatar__status_'.$id;
$MessageId = 'owner-main__message_'.$id;
?>

<?if($arResult["USER"]):?>
<?$frame = $this->createFrame()->begin("");?>
	<div class="owner-main" id="<?=$mainContainerId?>">
			<div class="owner-main-avatar">
				<?if($arResult["USER"]["PERSONAL_PHOTO"]):?>
					<div class="owner-main-avatar__item">
							<img src="<?=$arResult["USER"]["PERSONAL_PHOTO"]["SRC"]?>" alt="<?=$arResult["USER"]["NAME"]?> - аватар" />
					</div>
				<?else:?>
					<div class="owner-main-avatar__item owner-main-avatar__item-empty">
							<img src="<?=$templateFolder?>/img/img-profile.jpeg" alt="<?=$arResult["USER"]["NAME"]?> - аватар" />
					</div>
				<?endif;?>
				<div class="owner-main-avatar__status off" id="<?=$StatusId?>">
				   OFFLINE
				</div>
			</div>
		<div class="owner-main-body">
			<div class="owner-main-header">
				<div class="owner-main-header-main">
					<div class="owner-main__name"><?=$arResult["USER"]["NAME"]?></div>
					<div class="owner-main__date"><?=Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_ON_SITE')?> <?=$arResult["USER"]["DATE_REGISTER"]?></div>
				</div>
				<div class="owner-main-header-stat">
					<div class="owner-main-header-stat__item"><?=Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_OBJECTS')?>
					<?if($arResult["USER_OBJECTS_CNT"]>0):?>
						<a href="" data-block="objects-block" class="goto"><?=$arResult["USER_OBJECTS_CNT"]?></a>
					<?else:?>
						<span><?=$arResult["USER_OBJECTS_CNT"]?></span>
					<?endif;?>
					</div>
					<div class="owner-main-header-stat__item"><?=Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_REVIEWS')?>
							<a href="" data-block="owner-reviews" class="goto api-reviews-count">0</a>
					</div>
				</div>
			</div>
			<div class="owner-main__text txt">
			<?if(!empty($arResult["USER"]["UF_ABOUT"])):?>
				<?=$arResult["USER"]["UF_ABOUT"]?>
			<?else:?>
			    <?=Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_ABOUT')?>
			<?endif;?>
			</div>
		</div>
		<div class="owner-main-info">
			<a href="" class="owner-main__message" id="<?=$MessageId?>"><?=Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_SEND_MESSAGE')?></a>
			<div class="owner-main-info-contacts" id="<?=$injectId?>">
			</div>
		</div>
	</div>
	<script type="text/javascript">
		BX.ready(function () {
			BX.Raduga.UserContacts.init(
			{
				'containerId': '<?=$injectId?>',
				//'buttonID': '<?=$ButtunId?>',
				'url': '<?=$templateFolder?>/ajax.php',
				'uid': '<?=$arResult["USER"]["ID"]?>',
				'statusId': '<?=$StatusId?>',
				'MessageId': '<?=$MessageId?>',
				'mainContainerId': '<?=$mainContainerId?>',
				'elementID': '<?=$arParams["ELEMENT_ID"]?>',
			}
			);
		})
	</script>
<?
$frame->end();
return;
?>
<?endif;?>