<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Access\Permission{
/**
 * Class Permission.
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property int $sort
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Access\Role\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Permission\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Permission\Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Permission\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Permission\Permission whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Permission\Permission whereSort($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Permission\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Permission extends \Eloquent {}
}

namespace App\Models\Access\Role{
/**
 * Class Role.
 *
 * @property int $id
 * @property string $name
 * @property bool $all
 * @property int $sort
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read string $action_buttons
 * @property-read string $delete_button
 * @property-read string $edit_button
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Access\Permission\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Access\User\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Role\Role sort($direction = 'asc')
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Role\Role whereAll($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Role\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Role\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Role\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Role\Role whereSort($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\Role\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Role extends \Eloquent {}
}

namespace App\Models\Access\User{
/**
 * Class SocialLogin.
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_id
 * @property string $token
 * @property string $avatar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\SocialLogin whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\SocialLogin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\SocialLogin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\SocialLogin whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\SocialLogin whereProviderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\SocialLogin whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\SocialLogin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\SocialLogin whereUserId($value)
 * @mixin \Eloquent
 */
	class SocialLogin extends \Eloquent {}
}

namespace App\Models\Access\User{
/**
 * Class User.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property bool $status
 * @property string $confirmation_code
 * @property bool $confirmed
 * @property int $company_id
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Models\Company\Company $client
 * @property-read string $action_buttons
 * @property-read string $change_password_button
 * @property-read string $confirmed_button
 * @property-read string $confirmed_label
 * @property-read string $delete_button
 * @property-read string $delete_permanently_button
 * @property-read string $edit_button
 * @property-read string $login_as_button
 * @property-read mixed $picture
 * @property-read string $restore_button
 * @property-read string $show_button
 * @property-read string $status_button
 * @property-read string $status_label
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Access\User\SocialLogin[] $providers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Access\Role\Role[] $roles
 * @property-read \App\Models\Company\Company $supplier
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User active($status = true)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User confirmed($confirmed = true)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereConfirmationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereConfirmed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Access\User\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App\Models\Company{
/**
 * App\Models\Company\Company
 *
 * @property int $id
 * @property string $name
 * @property string $trn
 * @property string $customs
 * @property string $address
 * @property string $comment
 * @property string $logo
 * @property int $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reception\Reception[] $receptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Access\User\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereCustoms($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereLogo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereTrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company clients()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company\Company suppliers()
 */
	class Company extends \Eloquent {}
}

namespace App\Models\Delivery{
/**
 * App\Models\Delivery\Delivery
 *
 * @property int $id
 * @property int $client_id
 * @property string $delivery_number
 * @property \Carbon\Carbon $delivery_order_date
 * @property \Carbon\Carbon $delivery_preparation_date
 * @property \Carbon\Carbon $bl_date
 * @property bool $destination
 * @property bool $delivery_outside_working_hours
 * @property string $final_destination
 * @property string $po
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Company\Company $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reception\PackageItem[] $packageItems
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Company\Company[] $suppliers
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereBlDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereDeliveryNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereDeliveryOrderDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereDeliveryOutsideWorkingHours($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereDeliveryPreparationDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereDestination($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereFinalDestination($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery wherePo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Delivery\Delivery whereUpdatedAt($value)
 */
	class Delivery extends \Eloquent {}
}

namespace App\Models\History{
/**
 * Class History
 * package App.
 *
 * @property int $id
 * @property int $type_id
 * @property int $user_id
 * @property int $entity_id
 * @property string $icon
 * @property string $class
 * @property string $text
 * @property string $assets
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\History\HistoryType $type
 * @property-read \App\Models\Access\User\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereAssets($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereClass($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereIcon($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\History whereUserId($value)
 * @mixin \Eloquent
 */
	class History extends \Eloquent {}
}

namespace App\Models\History{
/**
 * Class HistoryType
 * package App.
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\HistoryType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\HistoryType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\HistoryType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History\HistoryType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class HistoryType extends \Eloquent {}
}

namespace App\Models\Inventory{
/**
 * App\Models\Inventory\Inventory
 *
 */
	class Inventory extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * App\Models\Product\Product
 *
 * @property int $id
 * @property string $reference
 * @property string $supplier_reference
 * @property string $designation
 * @property string $value
 * @property float $net_weight
 * @property float $brut_weight
 * @property bool $piece
 * @property string $unit
 * @property string $sap
 * @property int $supplier_id
 * @property mixed $custom_attributes
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reception\Package[] $packages
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereBrutWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereCustomAttributes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereDesignation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereNetWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product wherePiece($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereReference($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereSap($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereSupplierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereSupplierReference($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereUnit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product\Product whereValue($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Company\Company $supplier
 */
	class Product extends \Eloquent {}
}

namespace App\Models\Reception{
/**
 * App\Models\Reception\Package
 *
 * @property int $id
 * @property int $type
 * @property int $state
 * @property int $reception_id
 * @property string $batch_number
 * @property string $bin_location
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $packageItems
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereBatchNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereBinLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereReceptionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $deleted_at
 * @property-read \App\Models\Reception\Reception $reception
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Package whereDeletedAt($value)
 */
	class Package extends \Eloquent {}
}

namespace App\Models\Reception{
/**
 * App\Models\Reception\PackageItem
 *
 * @property int $id
 * @property int $product_id
 * @property int $package_id
 * @property int $subpackages_number
 * @property int $type
 * @property int $qty
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Delivery\Delivery[] $deliveries
 * @property-read \App\Models\Reception\Package $package
 * @property-read \App\Models\Product\Product $product
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\PackageItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\PackageItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\PackageItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\PackageItem wherePackageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\PackageItem whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\PackageItem whereQty($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\PackageItem whereSubpackagesNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\PackageItem whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\PackageItem whereUpdatedAt($value)
 */
	class PackageItem extends \Eloquent {}
}

namespace App\Models\Reception{
/**
 * App\Models\Reception\Reception
 *
 * @property int $id
 * @property int $supplier_id
 * @property int $client_id
 * @property string $reference
 * @property string $invoice_number
 * @property string $invoice_date
 * @property string $reception_date
 * @property string $planned_arrival_date
 * @property bool $returns
 * @property int $status
 * @property string $reserves
 * @property string $po
 * @property int $type
 * @property string $declaration_type
 * @property string $declaration_number
 * @property string $declaration_date
 * @property string $container_number
 * @property string $registration_number
 * @property string $driver
 * @property string $other
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reception\Package[] $packages
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereContainerNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDeclarationDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDeclarationNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDeclarationType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereDriver($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereInvoiceDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereOther($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception wherePlannedArrivalDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception wherePo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereReceptionDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereReference($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereReserves($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereReturns($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereSupplierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Reception\Reception whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Company\Company $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reception\PackageItem[] $items
 * @property-read \App\Models\Company\Company $supplier
 */
	class Reception extends \Eloquent {}
}

