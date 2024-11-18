<div class="top">
    <a href="index.php?action=admin">
        <svg version="1.1" id="fi_93634" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 219.151 219.151" style="enable-background:new 0 0 219.151 219.151;" xml:space="preserve">
            <g>
                <path d="M109.576,219.151c60.419,0,109.573-49.156,109.573-109.576C219.149,49.156,169.995,0,109.576,0S0.002,49.156,0.002,109.575
                C0.002,169.995,49.157,219.151,109.576,219.151z M109.576,15c52.148,0,94.573,42.426,94.574,94.575
                c0,52.149-42.425,94.575-94.574,94.576c-52.148-0.001-94.573-42.427-94.573-94.577C15.003,57.427,57.428,15,109.576,15z"></path>
                <path d="M94.861,156.507c2.929,2.928,7.678,2.927,10.606,0c2.93-2.93,2.93-7.678-0.001-10.608l-28.82-28.819l83.457-0.008
                c4.142-0.001,7.499-3.358,7.499-7.502c-0.001-4.142-3.358-7.498-7.5-7.498l-83.46,0.008l28.827-28.825
                c2.929-2.929,2.929-7.679,0-10.607c-1.465-1.464-3.384-2.197-5.304-2.197c-1.919,0-3.838,0.733-5.303,2.196l-41.629,41.628
                c-1.407,1.406-2.197,3.313-2.197,5.303c0.001,1.99,0.791,3.896,2.198,5.305L94.861,156.507z"></path>
            </g>
        </svg>
    </a>

    <h2>Informations article & gestionnaire des commentaires</h2>
</div>

<a href="index.php?action=admin-monitor" class="cursorNone">

    <div class="adminArticle">
        <?php

        // Vérifie que 'FILTER_SORT' & 'FILTER_VALUE' existe, si non, ajouter valeur par défault 
        $currentSort = filter_input(INPUT_POST, 'FILTER_SORT');
        $currentValue = filter_input(INPUT_POST, 'FILTER_VALUE');

        if (empty($currentSort)) $currentSort = "bottom";
        if (empty($currentValue)) $currentValue = "titleArticle";

        // Fonction pour générer les boutons de filtrage
        function generateFilterButton($currentSort, $currentValue, $label, $filterValue)
        {
            $sort = ($currentSort === "bottom" && $currentValue === $filterValue) ? "top" : "bottom";
            $icon = ($sort === "top") ? "icons/fleche-vers-le-bas.png" : "icons/chevron-en-haut.png";

            echo "<p>$label</p>";
            echo "<form class='btnFiltre' method='post'>";
            echo "<input class='inputHidden' type='text' name='FILTER_SORT' value='$sort'>";
            echo "<input class='inputHidden' type='text' name='FILTER_VALUE' value='$filterValue'>";
            echo "<input type='submit'>";
            if ($filterValue === $currentValue) {
                echo "<img src='$icon' alt=''>";
            }
            echo "</form>";
        }

        ?>
        <div class="articleLine">
            <div class="title">
                <?php generateFilterButton($currentSort, $currentValue, "Titre de l'article", "titleArticle"); ?>
            </div>
            <div class="numberView">
                <?php generateFilterButton($currentSort, $currentValue, "Nmb. de vues", "numberView"); ?>
            </div>
            <div class="nomberComment">
                <?php generateFilterButton($currentSort, $currentValue, "Nmb. de commentaires", "numberComment"); ?>
            </div>
            <div class="publicationDate">
                <?php generateFilterButton($currentSort, $currentValue, "Date de publication", "publicationDate"); ?>
            </div>
        </div>

        <?php

        // Fonctiuon trie pour gérer le filtre
        $sortDirection = ($currentSort === 'bottom') ? -1 : 1;

        usort($arrayMonitoring, function ($a, $b) use ($sortDirection, $currentValue) {
            return ($a[$currentValue] <=> $b[$currentValue]) * $sortDirection;
        });

        foreach ($arrayMonitoring as $key => $value) {

        ?>
            <div class="articleLine">
                <div class="title"><?= ($value["titleArticle"]); ?></div>
                <div class="numberView"><?= ($value["numberView"]); ?></div>
                <div class="nomberComment"><?= (($value["numberComment"])); ?></div>
                <div class="publicationDate"><?= ($value["publicationDate"]); ?></div>
            </div>
        <?php } ?>
    </div>
</a>

<div class="commentSpace" id="ancreComment">
    <div class="left">
        <?php foreach ($articles as $article) { ?>
            <a
                href="index.php?action=admin-monitor&id=<?= $article->getId() ?>#ancreComment" class="cardArticle">
                <?= $article->getTitle() ?>
            </a>
        <?php } ?>
    </div>
    <div class="right comments">
        <?php
        if (empty($comments)) {
            echo '<p class="info">Aucun commentaire pour cet article.</p>';
        } else {
            echo '<ul>';
            foreach ($comments as $comment) {
                echo '<li>';
                echo '  <div class="smiley">☻</div>';
                echo '  <div class="detailComment">';
                echo '      <h3 class="info">Le ' . Utils::convertDateToFrenchFormat($comment->getDateCreation()) . ", " . Utils::format($comment->getPseudo()) . ' a écrit :</h3>';
                echo '      <p class="content">' . Utils::format($comment->getContent()) . '</p>';
                echo '  </div>';
                echo "  <a href='index.php?action=admin-monitor&id={$comment->getIdArticle()}&idDelete={$comment->getId()}#ancreComment' ><img  src='icons/poubelle.png' alt=''></a>";
                echo '</li>';
            }
            echo '</ul>';
        }
        ?>
    </div>
</div>