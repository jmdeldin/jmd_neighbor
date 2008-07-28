<?php

$plugin['version'] = '0.1';
$plugin['author'] = 'Jon-Michael Deldin';
$plugin['author_uri'] = 'http://jmdeldin.com/';
$plugin['description'] = 'Pipes neighboring articles into an article form.';
$plugin['type'] = 0;

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
	<txp:jmd_neighbor type="prev"/>
	<txp:jmd_neighbor type="next"/>
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

fn1. If you need PHP4 compatibility, replace @public $type;@ with @var $type;@ and remove @public@ from the next line.

# --- END PLUGIN HELP ---

<?php
}

# --- BEGIN PLUGIN CODE ---

// pipe an article's neighbor into a form
function jmd_neighbor($atts)
{
	extract(lAtts(array(
		'form' => 'neighbor',
		'type' => '',
	), $atts));

	assert_article();
	global $thisarticle, $next_id, $prev_id, $jmd_neighbor;
	$jmd_neighbor= new JMD_Neighbor;

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
			return $jmd_neighbor->article($id, $form, $type);
		}
	}
	else
	{
		trigger_error('No type was set');
	}
}

// checks for neighbors
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
