<?php

$plugin = array(
    'description' => 'Pipes neighboring articles into an article form.',
    'type' => 0,
    'version' => '0.1',
);

if (!defined('txpinterface')) @include_once '../zem_tpl.php';

if (0) {
?>
# --- BEGIN PLUGIN HELP ---

@<txp:jmd_neighbor/>@ allows for next and previous articles to be output in an article form. Requires PHP5[1].

h2. Tag overview

|_. Tag  |_. Attributes |_. Description |
| @<txp:jmd_neighbor/>@ | form, type | Pipe a neighboring article into a form |
| @<txp:jmd_if_neighbor>@ | type | Checks for neighboring articles |

h2. @<txp:jmd_neighbor/>@

|_. Attribute |_. Available values |_. Default value |_. Description |
| @form@ | * | neighbor| The form for neighboring articles |
| @type@ | next, prev | - |  |

h2. @<txp:jmd_if_neighbor/>@

|_. Attribute |_. Available values |_. Default value |_. Description |
| @type@ | next, prev | - | If ommited, the tag checks for the presence of any neighbor. |

h2. Examples

h3. Link the article images of next and previous articles

Form: default

bc. <ul>
    <txp:jmd_neighbor form="neighbor" type="prev"/>
    <txp:jmd_neighbor form="neighbor" type="next"/>
</ul>

Form: neighbor

bc.. <li>
    <txp:jmd_if_neighbor type="next">
        Next:
    <txp:else/>
        Prev:
    </txp:jmd_if_neighbor>

    <txp:permlink>
        <txp:title/><txp:article_image thumbnail="1"/>
    </txp:permlink>
</li>

h3. Check for neighbors

Form: neighbor

bc. <txp:jmd_if_neighbor>
    Yes, there are neighbors.
<txp:else/>
    Nope, you're in Montana.
</txp:jmd_if_neighbor>

# --- END PLUGIN HELP ---

<?php
}

# --- BEGIN PLUGIN CODE ---

/**
 * Pipe an article's neighbor into a form.
 * 
 * @param array $atts
 * @param string $atts['form']
 * @param string $atts['type'] Valid: "prev" or "next"
 */
function jmd_neighbor($atts)
{
    global $thisarticle, $next_id, $prev_id, $jmd_neighbor;
    extract(lAtts(array(
        'form' => '',
        'type' => '',
    ), $atts));
    assert_article();

    if ($type == ('next' || 'prev'))
    {
        if (($type == 'next') && ($next_id))
        {
            $id = $next_id;
        }
        if (($type == 'prev') && ($prev_id))
        {
            $id = $prev_id;
        }
        if (isset($id))
        {
            $jmd_neighbor = new JMD_Neighbor();
            return $jmd_neighbor->article($id, $form, $type);
        }
    }
    else
    {
        trigger_error('No type was set');
    }
}

/**
 * Checks for neighbors.
 * 
 * @param array $atts
 * @param string $atts['type'] Type of neighbor to check for ('next'||'prev')
 */
function jmd_if_neighbor($atts, $thing)
{
    extract(lAtts(array(
        'type' => '',
    ), $atts));
    $condition = ($GLOBALS['jmd_neighbor']->type == $type);
    $out = EvalElse($thing, $condition);

    return parse($out);
}


class JMD_Neighbor
{
    public $type;

    /**
     * Creates an article_custom from a given id.
     * 
     * @param int $id Article ID
     * @param string $form Form name
     * @param string $type "next" or "prev"
     */
    public function article($id, $form, $type)
    {
        $this->type = $type;
        $out = article_custom(array(
            'form' => $form,
            'id' => $id,
        ));

        return $out;
    }
    
}

# --- END PLUGIN CODE ---

?>
