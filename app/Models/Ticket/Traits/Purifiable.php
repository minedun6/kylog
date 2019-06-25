<?php

namespace App\Models\Ticket\Traits;

use Purifier;

trait Purifiable
{
    /**
     * Updates the content and html attribute of the given model.
     *
     * @param string $rawHtml
     *
     * @return \Illuminate\Database\Eloquent\Model $this
     */
    public function setPurifiedContent($rawHtml)
    {
        $this->content = clean($rawHtml, ['HTML.Allowed' => '']);
        $this->html = clean($rawHtml);
    }

}